<?php declare(strict_types=1);

namespace Symplify\Monorepo\Console;

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symplify\Monorepo\Configuration\ConfigurationOptions;

final class Application extends SymfonyApplication
{
    public function __construct()
    {
        parent::__construct('Monorepo');
    }

    protected function getDefaultInputDefinition(): InputDefinition
    {
        $defaultInputDefinition = parent::getDefaultInputDefinition();

        $this->removeUnusedOptions($defaultInputDefinition);
        $this->addCustomOptions($defaultInputDefinition);

        return $defaultInputDefinition;
    }

    private function removeUnusedOptions(InputDefinition $inputDefinition): void
    {
        $options = $inputDefinition->getOptions();

        unset($options['quiet'], $options['version'], $options['no-interaction']);

        $inputDefinition->setOptions($options);
    }

    private function addCustomOptions(InputDefinition $inputDefinition): void
    {
        $inputDefinition->addOption(new InputOption(
            'config',
            null,
            InputOption::VALUE_REQUIRED,
            'Path to config file.',
            getcwd() . '/' . ConfigurationOptions::MONOREPO_CONFIG_FILE
        ));
    }
}
