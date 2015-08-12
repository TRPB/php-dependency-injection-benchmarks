<?php

namespace Njasm\Container\Tests\Adapter;

use Njasm\Container\Adapter\PimpleAdapter;
use Njasm\Container\Container;
use Pimple\Container as PimpleContainer;

class PimpleAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $pimple;
    protected $pimpleAdapter;
    protected $container;

    public function setUp()
    {
        $this->pimple = new PimpleContainer();
        $this->pimpleAdapter = new PimpleAdapter($this->pimple);
        $this->container = new Container();

        $this->container->provider($this->pimpleAdapter);
    }

    public function testPimpleHas()
    {
        $key = 'hello';
        $value = 'World';

        $this->registerPimpleDefinition($key, $value);
        $this->assertTrue($this->container->has($key));
    }

    public function testPimpleGet()
    {
        $key = 'hello';
        $value = 'World';

        $this->registerPimpleDefinition($key, $value);
        $this->assertEquals($value, $this->container->get($key));
    }

    public function testPimpleMultiInstance()
    {
        $key1 = 'hello';
        $value1 = 'world';
        $key2 = 'Night';
        $value2 = 'all';

        //register key1 in first instance, key2 in second instance
        $this->registerPimpleDefinition($key1, $value1);
        $pimple = new PimpleContainer();
        $pimple[$key2] = $value2;
        $adapter = new PimpleAdapter($pimple);
        $this->container->provider($adapter);

        $this->assertEquals($value1, $this->container->get($key1));
        $this->assertEquals($value2, $this->container->get($key2));
    }

    protected function registerPimpleDefinition($key, $value)
    {
        $this->pimple[$key] = $this->pimple->factory(
            function () use ($value) {
                return $value;
            }
        );
    }
}
