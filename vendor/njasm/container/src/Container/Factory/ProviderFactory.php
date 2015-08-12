<?php

namespace Njasm\Container\Factory;

use Njasm\Container\Definition\Builder\ProvidersBuilder;
use Njasm\Container\Definition\Service\Request;

class ProviderFactory implements FactoryInterface
{
    public function build(Request $request)
    {
        $builder = new ProvidersBuilder();

        return $builder->execute($request);
    }
}
