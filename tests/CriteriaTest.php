<?php

namespace PHPFluent\ArrayStorage;

/**
 * @covers PHPFluent\ArrayStorage\Criteria
 */
class CriteriaTest extends \PHPUnit_Framework_TestCase
{
    protected function filter()
    {
        return $this->getMock('PHPFluent\ArrayStorage\Filter\Filter');
    }

    public function testShouldAddFilter()
    {
        $criteria = new Criteria();
        $criteria->addFilter('foo', $this->filter());

        $this->assertCount(1, $criteria);
    }

    public function testShouldAcceptFiltersOnConstructor()
    {
        $filters = array(
            'foo' => $this->filter(),
            'bar' => $this->filter(),
        );
        $criteria = new Criteria($filters);

        $this->assertCount(2, $criteria);
    }

    public function testShouldAcceptFiltersAsKeyValueOnConstructor()
    {
        $filters = array(
            'foo' => true,
        );
        $criteria = new Criteria($filters);
        $filters = $criteria->getFilters();
        list($index, $filter) = $filters[0];

        $this->assertEquals('foo', $index);
        $this->assertInstanceOf('PHPFluent\\ArrayStorage\\Filter\\EqualTo', $filter);
    }

    public function testShouldReturnSelfWhenGettingANonExistentProperty()
    {
        $criteria = new Criteria();

        $this->assertSame($criteria, $criteria->foo);
    }

    public function testShouldReturnAddFilterUsingMethodOverload()
    {
        $criteria = new Criteria();
        $criteria->foo->equalTo(2);
        $filters = $criteria->getFilters();
        list($index, $filter) = $filters[0];

        $this->assertEquals('foo', $index);
        $this->assertInstanceOf('PHPFluent\\ArrayStorage\\Filter\\EqualTo', $filter);
    }

    /**
     * @expectedException UnexpectedValueException
     * @expectedExceptionMessage You first need to call a property for this filter
     */
    public function testShouldThrowExceptionWhenCallingAMethodBeforeCallAProperty()
    {
        $criteria = new Criteria();
        $criteria->equalTo(2);
    }

    /**
     * @expectedException BadMethodCallException
     * @expectedExceptionMessage "filter" is not a valid filter name
     */
    public function testShouldThrowExceptionWhenCallingMethodDoesNotRefersToAValidFilter()
    {
        $criteria = new Criteria();
        $criteria->foo->filter(2);
    }

    public function testShouldValidateUsingFiltersWhenRecordIsValid()
    {
        $criteria = new Criteria();
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
        $criteria = new Criteria();
        $criteria->foo->equalTo(2);
        $criteria->bar->equalTo(true);

        $record = new Record();
        $record->foo = 2;
        $record->bar = false;
        $record->baz = new Record();

        $this->assertFalse($criteria->isValid($record));
    }

    public function testShouldUseNotFilterWhenUsingTheWordNotInTheFilterName()
    {
        $criteria = new Criteria();
        $criteria->foo->notEqualTo(42);

        $record = new Record();
        $record->foo = 42;

        $this->assertFalse($criteria->isValid($record));
    }

    public function testShouldUseOneOfFilterWhenUsingTheWordOrInTheFilterName()
    {
        $criteria = new Criteria();
        $criteria->foo->greaterThanOrEqualTo(42);

        $record = new Record();
        $record->foo = 42;

        $this->assertTrue($criteria->isValid($record));
    }
}
