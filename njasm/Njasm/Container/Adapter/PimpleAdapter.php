<?php

namespace Njasm\Container\Adapter;

use Njasm\Container\ServicesProviderInterface;
use Pimple\Container as PimpleContainer;

class PimpleAdapter implements ServicesProviderInterface
{
    protected $container;
    
    public function __construct(PimpleContainer $container)
    {
        $this->container = $container;
    }
    
    public function get($id)
    {
        return $this->container->offsetGet($id);
    }

    public function has($id)
    {
        return $this->container->offsetExists($id);
    }
}
