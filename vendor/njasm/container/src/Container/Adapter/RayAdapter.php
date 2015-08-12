<?php

namespace Njasm\Container\Adapter;

use Njasm\Container\ServicesProviderInterface;
use Ray\Di\Container as RayContainer;

class RayAdapter implements ServicesProviderInterface
{
    protected $container;

    public function __construct(RayContainer $container)
    {
        $this->container = $container;
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function has($id)
    {
        return $this->container->has($id);
    }
}
