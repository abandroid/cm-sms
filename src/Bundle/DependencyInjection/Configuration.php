<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\CmSms\Bundle\DependencyInjection;

use Endroid\CmSms\Client;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder
            ->root('endroid_cm_sms')
                ->children()
                    ->booleanNode('enabled')->defaultTrue()->end()
                    ->scalarNode('product_token')->isRequired()->end()
                    ->arrayNode('defaults')
                        ->children()
                            ->scalarNode('unicode')
                                ->validate()
                                ->ifNotInArray(Client::getUnicodeOptions())
                                    ->thenInvalid('Invalid unicode mode %s, choose one from ["'.implode('", "', Client::getUnicodeOptions()).'"]')
                                ->end()
                            ->end()
                            ->scalarNode('sender')->end()
                            ->integerNode('minimum_number_of_message_parts')->min(1)->end()
                            ->integerNode('maximum_number_of_message_parts')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
