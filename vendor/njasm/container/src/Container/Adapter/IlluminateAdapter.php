<?php

namespace Njasm\Container\Adapter;

use Illuminate\Container\Container as IlluminateContainer;
use Njasm\Container\ServicesProviderInterface;

class IlluminateAdapter implements ServicesProviderInterface
{
    protected $container;

    public function __construct(IlluminateContainer $container)
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
