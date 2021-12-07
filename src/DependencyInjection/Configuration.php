<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\AllInklBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('nucleos_allinkl');

        $rootNode = $treeBuilder->getRootNode();

        $rootNode->append($this->getMailNode());
        $rootNode->append($this->getApiNode());
        $rootNode->append($this->getCheckNode());

        return $treeBuilder;
    }

    private function getMailNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder('mail'))->getRootNode();

        $node
            ->children()
                ->scalarNode('from')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('to')->isRequired()->cannotBeEmpty()->end()
            ->end()
        ;

        return $node;
    }

    private function getApiNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder('api'))->getRootNode();

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('auth_endpoint')->defaultValue('https://kasapi.kasserver.com/soap/wsdl/KasAuth.wsdl')->end()
                ->scalarNode('api_endpoint')->defaultValue('https://kasapi.kasserver.com/soap/wsdl/KasApi.wsdl')->end()
            ->end()
        ;

        return $node;
    }

    private function getCheckNode(): ArrayNodeDefinition
    {
        $node = (new TreeBuilder('check'))->getRootNode();

        $node
            ->fixXmlConfig('account')
            ->addDefaultsIfNotSet()
            ->children()
                ->integerNode('warning')->defaultValue(-5)->end()
                ->arrayNode('accounts')
                ->prototype('array')
                    ->children()
                        ->scalarNode('login')->end()
                        ->scalarNode('password')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
