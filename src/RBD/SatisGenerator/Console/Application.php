<?php

namespace RBD\SatisGenerator\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;

use RBD\SatisGenerator\SatisGenerator;
use RBD\SatisGenerator\Command\GenerateCommand;

/**
 * Application customisations.
 *
 * This runs satis-generator as a single command application.
 *
 * @see http://symfony.com/doc/current/components/console/single_command_tool.html
 * @author Max Bucknell <max.bucknell@redboxdigital.com>
 */
class Application extends BaseApplication
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
        parent::__construct('satis-generator', SatisGenerator::VERSION);
    }

    public function getCommandName(InputInterface $input)
    {
        return 'generate';
    }

    public function getConfig()
    {
        return $this->config;
    }

    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();

        $generateCommand = new GenerateCommand($this->getConfig());

        $defaultCommands[] = $generateCommand;

        return $defaultCommands;
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}

