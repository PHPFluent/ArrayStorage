<?php

namespace PHPFluent\ArrayStorage;

use PHPFluent\ArrayStorage\Filter;

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
        $expectedFilter = new Filter\EqualTo($inputFilters['foo']);

        $this->assertEquals('foo', $actualFilters[0][0]);
        $this->assertEquals($expectedFilter, $actualFilters[0][1]);
    }

    public function testShouldReturnInputIfInputIsAlreadyAFilter()
    {
        $filter = new Filter\EqualTo(42, 'identical');

        $factory = new Factory();

        $this->assertSame($filter, $factory->filter($filter));
    }

    public function testShouldCreateAFilterByNameAndArguments()
    {
        $expectedFilter = new Filter\EqualTo(42, 'identical');

        $factory = new Factory();
        $actualFilter = $factory->filter('equalTo', array(42, 'identical'));

        $this->assertEquals($expectedFilter, $actualFilter);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage "filter" is not a valid filter name
     */
    public function testShouldThrowExceptionWhenCallingMethodDoesNotRefersToAValidFilter()
    {
        $factory = new Factory();
        $factory->filter('filter');
    }

    public function testShouldUseNotFilterWhenInputHasNotAsPrefix()
    {
        $expectedFilter = new Filter\Not(new Filter\EqualTo(42));

        $factory = new Factory();
        $actualFilter = $factory->filter('notEqualTo', array(42));

        $this->assertEquals($expectedFilter, $actualFilter);
    }

    public function testShouldUseOneOfFilterWhenInputHasContainsTheWordOr()
    {
        $expectedFilter = new Filter\OneOf(
            array(
                new Filter\GreaterThan(42),
                new Filter\EqualTo(42),
            )
        );

        $factory = new Factory();
        $actualFilter = $factory->filter('greaterThanOrEqualTo', array(42));

        $this->assertEquals($expectedFilter, $actualFilter);
    }
}
