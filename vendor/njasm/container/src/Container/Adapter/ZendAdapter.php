<?php

namespace Njasm\Container\Adapter;

use Njasm\Container\ServicesProviderInterface;
use Zend\Di\Di as ZendContainer;
use Zend\Di\Exception\ClassNotFoundException;

class ZendAdapter implements ServicesProviderInterface
{
    protected $container;

    public function __construct(ZendContainer $container)
    {
        $this->container = $container;
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function has($id)
    {
        try {
            $this->container->get($id);
            return true;
        } catch (ClassNotFoundException $e) {
            return false;
        }
    }
}
