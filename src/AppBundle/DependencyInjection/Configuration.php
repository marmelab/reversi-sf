<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app');

        $this->addFacebookSection($rootNode);

        return $treeBuilder;
    }

    public function addFacebookSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
          ->children()
              ->arrayNode('facebook')
                  ->isRequired()
                  ->children()
                      ->scalarNode('access_token')->isRequired()->cannotBeEmpty()->end()
                      ->scalarNode('verify_token')->isRequired()->cannotBeEmpty()->end()
                  ->end()
              ->end()
          ->end();
    }
}
