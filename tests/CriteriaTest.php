<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage;

use PHPFluent\ArrayStorage\Filter\Filter;
use PHPUnit\Framework\TestCase;
use UnexpectedValueException;

/**
 * @covers PHPFluent\ArrayStorage\Criteria
 */
class CriteriaTest extends TestCase
{
    protected function filter(): Filter
    {
        return $this->createMock(Filter::class);
    }

    public function testShouldAddFilter(): void
    {
        $criteria = new Criteria(new Factory());
        $criteria->addFilter('foo', $this->filter());

        $this->assertCount(1, $criteria);
    }

    public function testShouldReturnSelfWhenGettingNonExistentProperty(): void
    {
        $criteria = new Criteria(new Factory());

        $this->assertSame($criteria, $criteria->foo);
    }

    public function testShouldAddFilterUsingMethodOverload(): void
    {
        $criteria = new Criteria(new Factory());
        $criteria->foo->equalTo(2);
        $filters = $criteria->getFilters();
        [$index, $filter] = $filters[0];

        $this->assertEquals('foo', $index);
        $this->assertInstanceOf('PHPFluent\\ArrayStorage\\Filter\\EqualTo', $filter);
    }

    public function testShouldUseFactoryToCreateFilters(): void
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

    public function testShouldThrowExceptionWhenCallingFilterBeforeCallProperty(): void
    {
        $this->expectExceptionObject(
            new UnexpectedValueException('You first need to call a property for this filter')
        );
        $criteria = new Criteria(new Factory());
        $criteria->equalTo(2);
    }

    public function testShouldValidateUsingFiltersWhenRecordIsValid(): void
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

    public function testShouldValidateUsingFiltersWhenRecordIsInvalid(): void
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
