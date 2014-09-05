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

    public function testShouldCreateNewRecord()
    {
        $factory = new Factory();
        $record = $factory->record();

        $this->assertInstanceOf(__NAMESPACE__ . '\\Record', $record);
    }

    public function testShouldCreateNewRecordFromArray()
    {
        $factory = new Factory();
        $data = array();
        $record = $factory->record($data);

        $this->assertInstanceOf(__NAMESPACE__ . '\\Record', $record);
    }

    public function testShouldCreateNewRecordFromStdClass()
    {
        $factory = new Factory();
        $data = new \stdClass();
        $record = $factory->record($data);

        $this->assertInstanceOf(__NAMESPACE__ . '\\Record', $record);
    }

    public function testShouldReturnTheSameInstanceWhenDataIsAlreadyARecordInstance()
    {
        $factory = new Factory();
        $data = new Record();
        $record = $factory->record($data);

        $this->assertSame($data, $record);
    }
}
