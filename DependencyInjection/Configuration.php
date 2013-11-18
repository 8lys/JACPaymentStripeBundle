<?php

namespace JAC\Payment\StripeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class Configuration
{
    public function getConfigTree()
    {
        $tb = new TreeBuilder();

        return $tb
            ->root('jac_payment_stripe', 'array')
                ->children()
                    ->scalarNode('api_key')->defaultValue('sk_test_mkGsLqEW6SLnZa487HYfJVLf')->end()
                    ->booleanNode('debug')->defaultValue('%kernel.debug%')->end()
                ->end()
            ->end()
            ->buildTree();
    }
}