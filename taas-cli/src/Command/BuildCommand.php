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

        $io = new SymfonyStyle($input, $output);

        $composerProcess = new Process(['composer', '-v']);
        if ($composerProcess->run() > 1) {
            $io->error('composer command not found.');
            return Command::FAILURE;
        }

        $io->info('Create project');

        // Currently, not possible, until taas-project is on packagist
        Process::fromShellCommandline('COMPOSER_MIRROR_PATH_REPOS=1 composer create-project --repository="${:repository_file}" "${:template}" "${:build_directory}" --no-interaction --no-install')
            ->run(null, ['template' => $this->composerTemplate, 'build_directory' => $this->buildDirectory, 'repository_file' => __DIR__ . '/../../packages.json']);

        $io->info('Add packages.');
        $this->addPackages();

        $io->info('Install project');
        Process::fromShellCommandline("composer install", $this->buildDirectory)->run();

        $io->info('Link custom code.');
        $this->linkCustomCode();
        return Command::SUCCESS;
    }

    protected function addPackages(): void {

        $config =  json_decode(file_get_contents($this->baseDirectory . '/.taasrc.json'), true);
        $composerFile = json_decode(file_get_contents($this->buildDirectory . '/composer.json'), true);

        $composerFile['repositories']['taas']['url'] = $config['endpoint'] . DIRECTORY_SEPARATOR . $config['version'];

        foreach ($config['modules'] as $module) {
            $composerFile['require'][$module] = "*";
        }

        file_put_contents($this->buildDirectory . '/composer.json', json_encode($composerFile, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
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

}
