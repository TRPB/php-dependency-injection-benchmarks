<?php

namespace Njasm\Container\Tests\Definition\Service;

use Njasm\Container\Definition\Definition;
use Njasm\Container\Definition\DefinitionsMap;
use Njasm\Container\Definition\DefinitionType;
use Njasm\Container\Definition\Service\DefinitionService;
use Njasm\Container\Definition\Service\Request;

class DefinitionServiceTest extends \PHPUnit_Framework_TestCase
{
    public $service;

    public function setUp()
    {
        $this->service = new DefinitionService();
    }

    public function testAssembleException()
    {
        $this->setExpectedException('OutOfBoundsException');
        $this->service->assemble("test-key", null);
    }
}
