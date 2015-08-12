<?php

namespace Njasm\Container\Adapter;

use Njasm\Container\ServicesProviderInterface;
use Orno\Di\Container as OrnoContainer;

class OrnoAdapter implements ServicesProviderInterface
{
    protected $container;

    public function __construct(OrnoContainer $container)
    {
        $this->container = $container;
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function has($id)
    {
        return $this->container->offsetExists($id);
    }
}
