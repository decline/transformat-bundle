<?php

namespace Decline\TransformatBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('decline_transformat');

        $rootNode
            ->children()
                ->scalarNode('directory')
                    ->info('The directory where the translation files are located.')
                    ->isRequired()
                ->end()
                ->arrayNode('xliff')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('extension')
                            ->info('The extension of the translation files which should be formatted.')
                            ->defaultValue('xlf')
                            ->values(['xlf', 'xliff'])
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('sourceLanguage')
                            ->info('The source language of the translation files.')
                            ->defaultValue('en')
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('namespace')
                            ->info('The xml namespace translation files.')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
