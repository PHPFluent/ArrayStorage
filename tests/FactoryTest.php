<?php

namespace PHPFluent\ArrayStorage;

use PHPFluent\ArrayStorage\Filter\EqualTo;

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

    public function testShouldCreateACriteria()
    {
        $factory = new Factory();
        $criteria = $factory->criteria();

        $this->assertInstanceOf(__NAMESPACE__ . '\\Criteria', $criteria);
    }

    public function testShouldCreateACriteriaFromAKeyValueArrayOfFilters()
    {
        $inputFilters = array(
            'foo' => $this->getMock(__NAMESPACE__ . '\\Filter\\Filter'),
        );
        $factory = new Factory();
        $criteria = $factory->criteria($inputFilters);
        $actualFilters = $criteria->getFilters();

        $this->assertEquals('foo', $actualFilters[0][0]);
        $this->assertSame($inputFilters['foo'], $actualFilters[0][1]);
    }

    public function testShouldCreateACriteriaFromAKeyValueArrayOfNonFilters()
    {
        $inputFilters = array(
            'foo' => true,
        );
        $factory = new Factory();
        $criteria = $factory->criteria($inputFilters);
        $actualFilters = $criteria->getFilters();
        $expectedFilter = new EqualTo($inputFilters['foo']);

        $this->assertEquals('foo', $actualFilters[0][0]);
        $this->assertEquals($expectedFilter, $actualFilters[0][1]);
    }
}
