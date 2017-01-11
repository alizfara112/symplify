<?php declare(strict_types=1);

namespace Symplify\ModularDoctrineFilters\DependencyInjection\Extension;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class SymplifyModularDoctrineFiltersExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $containerBuilder) : void
    {
        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../Resources/config'));
        $loader->load('services.yml');
    }
}
