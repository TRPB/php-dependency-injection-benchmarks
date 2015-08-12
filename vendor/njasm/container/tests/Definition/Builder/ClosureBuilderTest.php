<?php

namespace Njasm\Container\Tests\Definition\Builder;

use Njasm\Container\Container;

class ClosureBuilderTest extends \PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    public function testClosureWithAndWithoutArguments()
    {
        $defaultValue = "default@example.com";
        $nonDefaultValue = "john@doe.com";

        $this->container->set(
            "closure",
            function($to = "default@example.com") {
                return $to;
            }
        );

        // providing non empty arguments
        $this->assertNotEquals(
            $defaultValue,
            $this->container->get("closure", array($nonDefaultValue))
        );

        $this->assertEquals(
            $nonDefaultValue,
            $this->container->get("closure", array($nonDefaultValue))
        );

        // providing empty arguments to closure
        $this->assertEquals($defaultValue, $this->container->get("closure", array()));
        $this->assertEquals($defaultValue, $this->container->get("closure"));
    }
}
