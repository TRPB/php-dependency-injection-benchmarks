<?php

namespace Njasm\Container\Tests\Adapter;

use Njasm\Container\Adapter\OrnoAdapter;
use Njasm\Container\Container;
use Orno\Di\Container as OrnoContainer;

class OrnoAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $orno;
    protected $ornoAdapter;
    protected $container;

    public function setUp()
    {
        $this->orno = new OrnoContainer();
        $this->ornoAdapter = new OrnoAdapter($this->orno);
        $this->container = new Container();

        $this->container->provider($this->ornoAdapter);
    }

    public function testHasTrue()
    {
        $key = 'hello';
        $value = 'stdClass';
        $this->orno->add($key, $value);

        $this->assertTrue($this->container->has($key));
    }

    public function testHasFalse()
    {
        $this->assertFalse($this->container->has('non-existent'));
    }

    public function testGet()
    {
        $key = 'hello';
        $value = 'stdClass';
        $this->orno->add($key, $value);

        $this->assertInstanceOf($value, $this->container->get($key));
    }

    public function testDIMultiInstance()
    {
        $key1 = 'hello';
        $value1 = 'stdClass';
        $key2 = 'Night';
        $value2 = 'stdClass';

        //register key1 in first instance, key2 in second instance
        $this->orno->add($key1, $value1);

        $orno = new OrnoContainer();
        $orno->add($key2, $value2);
        $adapter = new OrnoAdapter($orno);
        $this->container->provider($adapter);

        $this->assertInstanceOf($value1, $this->container->get($key1));
        $this->assertInstanceOf($value2, $this->container->get($key2));
    }
}
