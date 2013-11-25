<?php

namespace JAC\Payment\StripeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration
{
    public function getConfigTree()
    {
        $tb = new TreeBuilder();

        return $tb
            ->root('jac_payment_stripe', 'array')
                ->children()
                    ->scalarNode('api_key')->defaultValue('sk_test_mkGsLqEW6SLnZa487HYfJVLf')->end()
                    ->scalarNode('publishable_key')->defaultValue('pk_test_czwzkTp2tactuLOEOqbMTRzG')->end()
                    ->booleanNode('debug')->defaultValue('%kernel.debug%')->end()
                ->end()
            ->end()
            ->buildTree();
    }
}
