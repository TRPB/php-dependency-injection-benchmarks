<?php

namespace Njasm\Container\Tests\Adapter;

use Njasm\Container\Adapter\SymfonyAdapter;
use Njasm\Container\Container;
use Symfony\Component\DependencyInjection\Container as SymfonyContainer;

class SymfonyAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $symfony;
    protected $symfonyAdapter;
    protected $container;

    public function setUp()
    {
        $this->symfony = new SymfonyContainer();
        $this->symfony->setParameter('hello', 'world');

        $this->symfonyAdapter = new SymfonyAdapter($this->symfony);
        $this->container = new Container();
        $this->container->provider($this->symfonyAdapter);
    }

    public function testHasFalse()
    {
        $this->assertFalse($this->container->has('non-existent'));
    }

    public function testHasTrueParameter()
    {
        $this->assertTrue($this->container->has('hello'));
    }

    public function testHasTrueService()
    {
        $this->symfony->set('world', new \stdClass());
        $this->assertTrue($this->container->has('world'));
    }

    public function testGet()
    {
        $this->assertEquals('world', $this->container->get('hello'));
    }
}
