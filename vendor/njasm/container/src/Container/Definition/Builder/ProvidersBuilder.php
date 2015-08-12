<?php

namespace Njasm\Container\Definition\Builder;

use Njasm\Container\Definition\Service\Request;

class ProvidersBuilder implements BuilderInterface
{
    public function execute(Request $request)
    {
        $key = $request->getKey();

        foreach ($request->getProviders() as $provider) {
            if ($provider->has($key)) {
                return $provider->get($key);
            }
        }
    }
}
