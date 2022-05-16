<?php

declare(strict_types=1);

namespace Taas\Cli\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'build',
    description: 'Build TaaS project.',
)]
class BuildCommand extends TaasCommandBase
{

    private string $composerTemplate;
    private iterable $optionalThunderPackages;

    public function __construct(string $baseDirectory, string $configDirectory, string $buildDirectory, string $documentRootDirectory, string $composerTemplate, iterable $optionalThunderPackages)
    {
        parent::__construct($baseDirectory, $configDirectory, $buildDirectory, $documentRootDirectory);

        $this->composerTemplate = $composerTemplate;

        // TODO: remove list of optional packages from services.yml
        $this->optionalThunderPackages = $optionalThunderPackages;
    }

    protected function configure(): void
    {
        $this->addArgument('base-directory', InputArgument::OPTIONAL, 'The base directory of the project. Defaults to current directory.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Override base directory, if necessary.
        $this->baseDirectory = $input->getArgument('base-directory')?:$this->baseDirectory;
        $composerTemplate = $this->composerTemplate;

        $io = new SymfonyStyle($input, $output);

        $composerProcess = new Process(['composer', '-v']);
        if ($composerProcess->run() > 1) {
            $io->error('composer command not found.');
            return Command::FAILURE;
        }

        $io->info('Create project');

        // Currently not possible, until taas-project is on packagist
        /*
        Process::fromShellCommandline('composer create-project "${:template}" "${:build_directory}" --no-interaction --no-install')
            ->run(null, ['template' => $composerTemplate, 'build_directory' => $buildDirectory]);
        */
        // For now, copy it from local directory
        $filesystem = new Filesystem();
        if($filesystem->exists($this->baseDirectory . '/../taas-build-project')) {
            $filesystem->mirror($this->baseDirectory . '/../taas-build-project', $this->buildDirectory);
        }
        else {
            $io->error('Project template not found');
            return Command::FAILURE;
        }

        $io->info('Remove unused modules.');
        $this->removeUnusedModules();

        $io->info('Install project');
        Process::fromShellCommandline("composer install", $this->buildDirectory)->run();

        $io->info('Link custom code.');
        $this->linkCustomCode();
        return Command::SUCCESS;
    }

    /**
     * Link custom modules, themes and config into project.
     */
    protected function linkCustomCode(): void
    {
        $filesystem = new Filesystem();
        if ($filesystem->exists($this->customModulesSourceDirectory)) {
            $filesystem->symlink($this->customModulesSourceDirectory, $this->documentRootDirectory . '/modules/custom');
        }

        if ($filesystem->exists($this->customThemesSourceDirectory)) {
            $filesystem->symlink($this->customThemesSourceDirectory, $this->documentRootDirectory . '/themes/custom');
        }

        if ($filesystem->exists($this->configDirectory)) {
            $filesystem->symlink($this->configDirectory, $this->buildDirectory . '/config/sync');
        }
    }

    /**
     * Remove dependencies on Drupal modules, that are not enabled.
     *
     * TODO: We have to implement a mechanism, that prevents removal if dependency is still enabled on production.
     * @return void
     */
    protected function removeUnusedModules(): void
    {
        $coreExtension = Yaml::parseFile($this->configDirectory. '/core.extension.yml');
        $enabledExtensions = array_merge($coreExtension['module'], $coreExtension['theme']);
        $optionalThunderPackages = $this->optionalThunderPackages;

        $composerFile = json_decode(file_get_contents($this->buildDirectory . '/composer.json'), true);

        if (!isset($composerFile['replace'])){
            $composerFile['replace'] = [];
        }

        // Do not install modules, that are not enabled.
        // TODO: decide if we should install them on develop
        foreach ($optionalThunderPackages as $extension => $package) {
            if (!isset($enabledExtensions[$extension])) {
                $composerFile['replace'][$package] = "*";
            }
        }

        file_put_contents($this->buildDirectory . '/composer.json', json_encode($composerFile, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
