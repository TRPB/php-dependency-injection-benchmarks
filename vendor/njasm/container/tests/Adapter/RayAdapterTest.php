<?php

namespace Njasm\Container\Tests\Adapter;

use Aura\Di\Config;
use Aura\Di\Forge;
use Njasm\Container\Adapter\RayAdapter;
use Njasm\Container\Container;
use Ray\Di\Container as RayContainer;

class RayAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $ray;
    protected $rayAdapter;
    protected $container;

    public function setUp()
    {
        $this->ray = new RayContainer(new Forge(new Config()));
        $this->rayAdapter = new RayAdapter($this->ray);
        $this->container = new Container();

        $this->container->provider($this->rayAdapter);
    }

    public function testHasTrue()
    {
        $key = 'hello';
        $value = new \stdClass();
        $this->ray->set($key, $value);

        $this->assertTrue($this->container->has($key));
    }

    public function testHasFalse()
    {
        $this->assertFalse($this->container->has('non-existent'));
    }

    public function testGet()
    {
        $key = 'hello';
        $value = new \stdClass();
        $this->ray->set($key, $value);

        $this->assertEquals($value, $this->container->get($key));
    }

    public function testDIMultiInstance()
    {
        $key1 = 'hello';
        $value1 = new \stdClass();
        $key2 = 'Night';
        $value2 = new \stdClass();

        //register key1 in first instance, key2 in second instance
        $this->ray->set($key1, $value1);

        $ray = new RayContainer(new Forge(new Config()));
        $ray->set($key2, $value2);
        $adapter = new RayAdapter($ray);
        $this->container->provider($adapter);

        $this->assertEquals($value1, $this->container->get($key1));
        $this->assertEquals($value2, $this->container->get($key2));
    }
}
