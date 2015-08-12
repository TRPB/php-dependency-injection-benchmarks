<?php

namespace Njasm\Container\Tests\Adapter;

use Illuminate\Container\Container as IlluminateContainer;
use Njasm\Container\Adapter\IlluminateAdapter;
use Njasm\Container\Container;

class IlluminateAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $illuminate;
    protected $illuminateAdapter;
    protected $container;

    public function setUp()
    {
        $this->illuminate = new IlluminateContainer();
        $this->illuminateAdapter = new IlluminateAdapter($this->illuminate);
        $this->container = new Container();

        $this->container->provider($this->illuminateAdapter);
    }

    public function testHasTrue()
    {
        $key = 'hello';
        $value = '\\stdClass';

        $this->illuminate->bind($key, $value);

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
        $this->illuminate->offsetSet($key, $value);

        $this->assertInstanceOf('\\stdClass', $this->container->get($key));
    }

    public function testDIMultiInstance()
    {
        $key1 = 'hello';
        $value1 = new \stdClass();
        $key2 = 'Night';
        $value2 = new \stdClass();

        //register key1 in first instance, key2 in second instance
        $this->illuminate->offsetSet($key1, $value1);

        $illuminate = new IlluminateContainer();
        $illuminate->offsetSet($key2, $value2);
        $adapter = new IlluminateAdapter($illuminate);
        $this->container->provider($adapter);

        $this->assertEquals($value1, $this->container->get($key1));
        $this->assertEquals($value2, $this->container->get($key2));
    }
}
