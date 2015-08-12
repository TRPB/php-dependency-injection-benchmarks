<?php

namespace Njasm\Container\Adapter;

use Joomla\DI\Container as JoomlaContainer;
use Njasm\Container\ServicesProviderInterface;

class JoomlaAdapter implements ServicesProviderInterface
{
    protected $container;

    public function __construct(JoomlaContainer $container)
    {
        $this->container = $container;
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function has($id)
    {
        return $this->container->exists($id);
    }
}
