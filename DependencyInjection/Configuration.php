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
                ->arrayNode('xliff')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('sourceLanguage')
                            ->defaultValue('en')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('directory')
                    ->info('The directory where the translation files are located.')
                    ->isRequired()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
