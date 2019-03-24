<?php

namespace PHPFluent\ArrayStorage;

/**
 * @covers PHPFluent\ArrayStorage\Criteria
 */
class CriteriaTest extends \PHPUnit\Framework\TestCase
{
    protected function filter()
    {
        return $this->createMock('PHPFluent\ArrayStorage\Filter\Filter');
    }

    public function testShouldAddFilter()
    {
        $criteria = new Criteria(new Factory());
        $criteria->addFilter('foo', $this->filter());

        $this->assertCount(1, $criteria);
    }

    public function testShouldReturnSelfWhenGettingANonExistentProperty()
    {
        $criteria = new Criteria(new Factory());

        $this->assertSame($criteria, $criteria->foo);
    }

    public function testShouldAddFilterUsingMethodOverload()
    {
        $criteria = new Criteria(new Factory());
        $criteria->foo->equalTo(2);
        $filters = $criteria->getFilters();
        list($index, $filter) = $filters[0];

        $this->assertEquals('foo', $index);
        $this->assertInstanceOf('PHPFluent\\ArrayStorage\\Filter\\EqualTo', $filter);
    }

    public function testShouldUseFactoryToCreateFilters()
    {
        $filter = $this->createMock('PHPFluent\\ArrayStorage\\Filter\\Filter');

        $factory = $this->createMock('PHPFluent\\ArrayStorage\\Factory');
        $factory
            ->expects($this->once())
            ->method('filter')
            ->with('someFilter')
            ->will($this->returnValue($filter));

        $criteria = new Criteria($factory);
        $criteria->foo->someFilter(2);
    }

    public function testShouldThrowExceptionWhenCallingAFilterBeforeCallAProperty()
    {
        $this->expectExceptionObject(
            new \UnexpectedValueException('You first need to call a property for this filter')
        );
        $criteria = new Criteria(new Factory());
        $criteria->equalTo(2);
    }

    public function testShouldValidateUsingFiltersWhenRecordIsValid()
    {
        $criteria = new Criteria(new Factory());
        $criteria->foo->equalTo(2);
        $criteria->bar->equalTo(false);

        $record = new Record();
        $record->foo = 2;
        $record->bar = false;
        $record->baz = new Record();

        $this->assertTrue($criteria->isValid($record));
    }

    public function testShouldValidateUsingFiltersWhenRecordIsInvalid()
    {
        $criteria = new Criteria(new Factory());
        $criteria->foo->equalTo(2);
        $criteria->bar->equalTo(true);

        $record = new Record();
        $record->foo = 2;
        $record->bar = false;
        $record->baz = new Record();

        $this->assertFalse($criteria->isValid($record));
    }
}
