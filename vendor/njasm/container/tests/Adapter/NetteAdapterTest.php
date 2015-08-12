<?php

namespace Njasm\Container\Tests\Adapter;

use Nette\DI\Container as NetteContainer;
use Njasm\Container\Adapter\NetteAdapter;
use Njasm\Container\Container;

class NetteAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $nette;
    protected $netteAdapter;
    protected $container;

    public function setUp()
    {
        $this->nette = new NetteContainer();
        $this->netteAdapter = new NetteAdapter($this->nette);
        $this->container = new Container();

        $this->container->provider($this->netteAdapter);
    }

    public function testHasFalse()
    {
        $returnValue = $this->container->has('non-existent-nor-instantiatiable');

        $this->assertFalse($returnValue);
    }

    public function testHasTrue()
    {
        $key = 'hello';
        $value = new \stdClass();
        $this->nette->addService($key, $value);

        $this->assertTrue($this->container->has($key));
    }

    public function testGet()
    {
        $key = 'hello';
        $value = new \stdClass();
        $this->nette->addService($key, $value);

        $returnValue = $this->container->get('hello');
        $this->assertInstanceOf('\stdClass', $returnValue);
    }

    public function testDIMultiInstance()
    {
        $key1 = 'hello';
        $value1 = new \stdClass();
        $key2 = 'Night';
        $value2 = new \stdClass();

        //register key1 in first instance, key2 in second instance
        $this->nette->addService($key1, $value1);

        $nette = new NetteContainer();
        $nette->addService($key2, $value2);
        $adapter = new NetteAdapter($nette);
        $this->container->provider($adapter);

        $this->assertInstanceOf('stdClass', $this->container->get($key1));
        $this->assertInstanceOf('stdClass', $this->container->get($key2));
    }
}
