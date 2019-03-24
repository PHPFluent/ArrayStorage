<?php

namespace PHPFluent\ArrayStorage;

/**
 * @covers PHPFluent\ArrayStorage\Factory
 */
class FactoryTest extends \PHPUnit\Framework\TestCase
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
            'foo' => $this->createMock(__NAMESPACE__ . '\\Filter\\Filter'),
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

    public function operatorsProvider()
    {
        return array(
            array('=', 42, new Filter\EqualTo(42)),
            array('!=', 42, new Filter\Not(new Filter\EqualTo(42))),
            array('<', 42, new Filter\LessThan(42)),
            array('<=', 42, new Filter\OneOf(array(new Filter\LessThan(42), new Filter\EqualTo(42)))),
            array('>', 42, new Filter\GreaterThan(42)),
            array('>=', 42, new Filter\OneOf(array(new Filter\GreaterThan(42), new Filter\EqualTo(42)))),
            array('BETWEEN', array(1, 3), new Filter\Between(1, 3)),
            array('NOT BETWEEN', array(1, 3), new Filter\Not(new Filter\Between(1, 3))),
            array('ILIKE', 'String%', new Filter\ILike('String%')),
            array('NOT ILIKE', 'String%', new Filter\Not(new Filter\ILike('String%'))),
            array('IN', array(1, 2, 3), new Filter\In(array(1, 2, 3))),
            array('NOT IN', array(1, 2, 3), new Filter\Not(new Filter\In(array(1, 2, 3)))),
            array('LIKE', 'String%', new Filter\Like('String%')),
            array('NOT LIKE', 'String%', new Filter\Not(new Filter\Like('String%'))),
            array('REGEX', '/[a-z]/', new Filter\Regex('/[a-z]/')),
            array('NOT REGEX', '/[a-z]/', new Filter\Not(new Filter\Regex('/[a-z]/'))),
        );
    }

    /**
     * @dataProvider operatorsProvider
     */
    public function testShouldCreateACriteriaFromAKeyValueArrayOfNonFiltersUsingOperator($operator, $value, $expectedFilter)
    {
        $inputFilters = array(
            'name ' . $operator => $value,
        );
        $factory = new Factory();
        $criteria = $factory->criteria($inputFilters);
        $actualFilters = $criteria->getFilters();

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

    public function testShouldThrowExceptionWhenCallingMethodDoesNotRefersToAValidFilter()
    {
        $this->expectExceptionObject(
            new \InvalidArgumentException('"filter" is not a valid filter name')
        );
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
