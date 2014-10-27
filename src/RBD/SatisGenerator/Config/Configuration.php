<?php

namespace RBD\SatisGenerator\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('satis-generator');

        $rootNode
            ->children()
                ->arrayNode('output')
                    ->children()
                        ->scalarNode('name')->end()
                        ->scalarNode('description')->end()
                        ->scalarNode('homepage')->end()
                        ->scalarNode('output-dir')
                            ->defaultValue('public/')
                        ->end()
                        ->scalarNode('file')
                            ->defaultValue('satis.json')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('connection')
                    ->children()
                        ->enumNode('api')
                            ->values(array('github-org', 'bitbucket'))
                        ->end()
                        ->scalarNode('account')->end()
                        ->scalarNode('username')->end()
                        ->scalarNode('password')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

