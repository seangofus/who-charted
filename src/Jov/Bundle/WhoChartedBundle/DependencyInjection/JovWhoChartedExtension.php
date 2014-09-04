<?php

namespace Jov\Bundle\WhoChartedBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

use Oro\Component\Config\Loader\CumulativeConfigLoader;
use Oro\Component\Config\Loader\YamlCumulativeFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 */
class JovWhoChartedExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $chartConfigs = array();
        $mergedConfig = array();

        $configLoader = new CumulativeConfigLoader(
            'jov_who_charted',
            new YamlCumulativeFileLoader('Resources/config/jov/who_charted.yml')
        );

        $resources = $configLoader->load($container);
        foreach ($resources as $resource) {
            $mergedConfig = array_replace_recursive($mergedConfig, $resource->data['jov_who_charted']);
        }

        foreach ($configs as $config) {
            $mergedConfig = array_replace_recursive($mergedConfig, $config);
        }

        $chartConfigs[] = $mergedConfig;

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $chartConfigs);

        $container->getDefinition('who.charted.config_provider')->replaceArgument(0, $config);
    }
}
