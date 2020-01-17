<?php

namespace Siganushka\RBACBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('siganushka_rbac');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('firewall_pattern')
                    ->defaultNull()
                ->end()
                ->scalarNode('ignore_pattern')
                    ->cannotBeEmpty()
                    ->defaultValue('^/(_(profiler|error|wdt)|css|images|js)/')
                ->end()
                ->scalarNode('translation_domain')
                    ->defaultValue('siganushka_rbac')
                ->end()
                ->arrayNode('fully_routes')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('remembered_routes')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('anonymously_routes')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
