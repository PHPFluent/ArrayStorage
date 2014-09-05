<?php

namespace PHPFluent\ArrayStorage;

/**
 * @covers PHPFluent\ArrayStorage\Factory
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldCreateACollection()
    {
        $factory = new Factory();

        $this->assertInstanceOf('PHPFluent\\ArrayStorage\\Collection', $factory->collection());
    }

    public function testShouldReturnInputIfInputIsAlreadyACollection()
    {
        $collection = $this
            ->getMockBuilder('PHPFluent\\ArrayStorage\\Collection')
            ->disableOriginalConstructor()
            ->getMock();

        $factory = new Factory();

        $this->assertSame($collection, $factory->collection($collection));
    }
}
