<?php

namespace Njasm\Container\Tests\Adapter;

use Aura\Di\Config;
use Aura\Di\Container as AuraContainer;
use Aura\Di\Forge;
use Njasm\Container\Adapter\AuraAdapter;
use Njasm\Container\Container;

/**
 * @requires PHP 5.4
 */
class AuraAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $aura;
    protected $auraAdapter;
    protected $container;

    public function setUp()
    {
        $this->aura = new AuraContainer(new Forge(new Config()));
        $this->auraAdapter = new AuraAdapter($this->aura);
        $this->container = new Container();

        $this->container->provider($this->auraAdapter);
    }

    public function testHasTrue()
    {
        $key = 'hello';
        $value = new \stdClass();

        $this->aura->set($key, $value);

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
        $this->aura->set($key, $value);

        $this->assertEquals($value, $this->container->get($key));
    }

    public function testDIMultiInstance()
    {
        $key1 = 'hello';
        $value1 = new \stdClass();
        $key2 = 'Night';
        $value2 = new \stdClass();

        //register key1 in first instance, key2 in second instance
        $this->aura->set($key1, $value1);

        $aura = new AuraContainer(new Forge(new Config()));
        $aura->set($key2, $value2);
        $adapter = new AuraAdapter($aura);
        $this->container->provider($adapter);

        $this->assertEquals($value1, $this->container->get($key1));
        $this->assertEquals($value2, $this->container->get($key2));
    }
}
