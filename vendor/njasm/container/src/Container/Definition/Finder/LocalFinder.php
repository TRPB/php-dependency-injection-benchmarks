<?php

namespace Njasm\Container\Definition\Finder;

use Njasm\Container\Definition\Service\Request;

class LocalFinder extends AbstractFinder
{
    protected function handle(Request $request)
    {
        $key            = $request->getKey();
        $definitions    = $request->getDefinitionsMap();

        return $definitions->has($key);
    }
}
