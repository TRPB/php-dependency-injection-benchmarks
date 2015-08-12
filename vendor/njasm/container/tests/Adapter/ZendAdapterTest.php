<?php

namespace Njasm\Container\Tests\Adapter;

use Njasm\Container\Adapter\ZendAdapter;
use Njasm\Container\Container;
use Zend\Di\Di;

class ZendAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected $zend;
    protected $zendAdapter;
    protected $container;

    public function setUp()
    {
        $this->zend = new Di();
        $this->zendAdapter = new ZendAdapter($this->zend);
        $this->container = new Container();

        $this->container->provider($this->zendAdapter);
    }

    public function testGet()
    {
        $returnValue = $this->zendAdapter->get('stdClass');

        $this->assertInstanceOf('\stdClass', $returnValue);
    }

    public function testHasFalse()
    {
        $returnValue = $this->zendAdapter->has('non-existent-nor-instantiatiable');

        $this->assertFalse($returnValue);
    }

    public function testHasTrue()
    {
        $returnValue = $this->zendAdapter->has('stdClass');

        $this->assertTrue($returnValue);
    }
}
