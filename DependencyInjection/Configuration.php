<?php


namespace USJ\GSuiteBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder
            ->root('usj_gsuite', 'array')
            ->children();

        $rootNode
            ->arrayNode('clients')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('key')->isRequired()->end()
                        ->scalarNode('subject')->defaultValue('')->end()
                        ->arrayNode('scopes')
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
