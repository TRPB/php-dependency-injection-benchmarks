<?php

namespace Njasm\Container\Tests\Adapter;

use Joomla\DI\Container as JoomlaContainer;
use Njasm\Container\Adapter\JoomlaAdapter;
use Njasm\Container\Container;

class JoomlaAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $joomla;
    protected $joomlaAdapter;
    protected $container;

    public function setUp()
    {
        $this->joomla = new JoomlaContainer();
        $this->joomlaAdapter = new JoomlaAdapter($this->joomla);
        $this->container = new Container();

        $this->container->provider($this->joomlaAdapter);
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
        $this->joomla->set($key, $value);

        $this->assertTrue($this->container->has($key));
    }

    public function testGet()
    {
        $key = 'hello';
        $value = new \stdClass();
        $this->joomla->set($key, $value);

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
        $this->joomla->set($key1, $value1);

        $joomla = new JoomlaContainer();
        $joomla->set($key2, $value2);
        $adapter = new JoomlaAdapter($joomla);
        $this->container->provider($adapter);

        $this->assertInstanceOf('stdClass', $this->container->get($key1));
        $this->assertInstanceOf('stdClass', $this->container->get($key2));
    }
}
