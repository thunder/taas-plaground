<?php

declare(strict_types=1);

namespace Taas\Cli\Command;

use Symfony\Component\Console\Command\Command;

abstract class TaasCommandBase extends Command {

    protected string $baseDirectory;
    protected string $configDirectory;
    protected string $buildDirectory;
    protected string $documentRootDirectory;
    protected string $customModulesSourceDirectory;
    protected string $customThemesSourceDirectory;

    public function __construct(string $baseDirectory, string $configDirectory, string $buildDirectory, string $documentRootDirectory)
    {
        parent::__construct();

        $this->baseDirectory = $baseDirectory;
        $this->buildDirectory = $this->baseDirectory . '/' . $buildDirectory;
        $this->configDirectory = $this->baseDirectory . '/' . $configDirectory;
        $this->documentRootDirectory = $this->buildDirectory . '/' . $documentRootDirectory;
        $this->customModulesSourceDirectory = $this->baseDirectory . '/modules';
        $this->customThemesSourceDirectory = $this->baseDirectory . '/themes';
    }
}
