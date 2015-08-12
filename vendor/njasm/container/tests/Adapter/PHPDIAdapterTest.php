<?php

namespace Njasm\Container\Tests\Adapter;

use DI\ContainerBuilder;
use Njasm\Container\Adapter\PHPDIAdapter;
use Njasm\Container\Container;

class PHPDIAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $phpDI;
    protected $phpDiAdapter;
    protected $container;

    public function setUp()
    {
        $builder = new ContainerBuilder();
        $this->phpDI = $builder->build();

        $this->phpDiAdapter = new PHPDIAdapter($this->phpDI);
        $this->container = new Container();

        $this->container->provider($this->phpDiAdapter);
    }

    public function testDIHasTrue()
    {
        $key = 'hello';
        $value = 'world';
        $this->phpDI->set($key, $value);

        $this->assertTrue($this->container->has('hello'));
    }

    public function testDIHasFalse()
    {
        $this->assertFalse($this->container->has('non-existent'));
    }

    public function testDIGet()
    {
        $key = 'hello';
        $value = 'world';
        $this->phpDI->set($key, $value);

        $this->assertEquals($value, $this->container->get($key));
    }

    public function testDIMultiInstance()
    {
        $key1 = 'hello';
        $value1 = 'world';
        $key2 = 'Night';
        $value2 = 'all';

        //register key1 in first instance, key2 in second instance
        $this->phpDI->set($key1, $value1);

        $builder = new ContainerBuilder();
        $phpDi = $builder->build();
        $phpDi->set($key2, $value2);
        $adapter = new PHPDIAdapter($phpDi);
        $this->container->provider($adapter);

        $this->assertEquals($value1, $this->container->get($key1));
        $this->assertEquals($value2, $this->container->get($key2));
    }
}
