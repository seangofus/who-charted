<?php

namespace Jov\Bundle\WhoChartedBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('jov_who_charted')
            ->info('Configuration of charts')
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->scalarNode('label')
                        ->info('The label of chart')
                        ->cannotBeEmpty()
                        ->isRequired()
                    ->end()
                    ->arrayNode('default_settings')
                        ->info('Default settings of chart')
                        ->prototype('variable')
                        ->end()
                    ->end()
                    ->scalarNode('template')
                        ->info('Template of chart')
                        ->cannotBeEmpty()
                        ->isRequired()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
