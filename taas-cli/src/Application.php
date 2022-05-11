<?php

declare(strict_types=1);

namespace Taas\Cli;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * Constructor.
     */
    public function __construct(iterable $commands, string $version)
    {
        parent::__construct('taas', $version);

        foreach ($commands as $command) {
            $this->add($command);
        }
    }
}