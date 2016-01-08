<?php

namespace tests\Framework;

use Framework\ControllerFactory;

class ControllerFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateController()
    {
        $factory = new ControllerFactory();
        $this->assertInstanceOf(FooBar::class, $factory->createController(['_controller' => FooBar::class]));
    }

    /**
     * @expectedException  \RuntimeException
     */
    public function testControllerClassDoesNotExist()
    {
        $factory = new ControllerFactory();
        $factory->createController(['_controller' => 'FOOOOOOOOO']);
    }

    /**
     * @expectedException  \RuntimeException
     */
    public function testCreateNameIsNotDefined()
    {
        $factory = new ControllerFactory();
        $factory->createController(['foo' => 'bar']);
    }

    /**
     * @expectedException  \RuntimeException
     */
    public function testCreateNotInvokableController()
    {
        $factory = new ControllerFactory();
        $factory->createController(['_controller' => 'stdClass']);
    }
}

Class FooBar
{
    public function __invoke()
    {

    }
}