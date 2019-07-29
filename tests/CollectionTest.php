<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage;

use PHPFluent\ArrayStorage\Filter\Filter;
use PHPUnit\Framework\TestCase;
use function iterator_to_array;

/**
 * @covers PHPFluent\ArrayStorage\Collection
 */
class CollectionTest extends TestCase
{
    public function testShouldUseFactoryToCreateRecords(): void
    {
        $data = ['foo' => true];
        $record = new Record($data);

        $factory = $this
            ->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $factory
            ->expects($this->once())
            ->method('record')
            ->with($data)
            ->will($this->returnValue($record));

        $collection = new Collection($factory);

        $this->assertSame($record, $collection->record($data));
    }

    public function testShouldUseFactoryToCreateCriteria(): void
    {
        $factory = $this
            ->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $filters = ['foo' => $this->createMock(Filter::class)];

        $criteria = new Criteria($factory);
        $criteria->addFilter('foo', $filters['foo']);

        $factory
            ->expects($this->once())
            ->method('criteria')
            ->with($filters)
            ->will($this->returnValue($criteria));

        $collection = new Collection($factory);

        $this->assertSame($criteria, $collection->criteria($filters));
    }

    public function testShouldCountRecordsInCollection(): void
    {
        $collection = new Collection(new Factory());

        $this->assertCount(0, $collection);
    }

    public function testShouldInsertRecordToCollection(): void
    {
        $collection = new Collection(new Factory());
        $record = new Record(['id' => 1, 'name' => 'Jéssica Santana']);
        $collection->insert($record);

        $this->assertCount(1, $collection);
    }

    public function testShouldInsertRecordToCollectionAndCreateIdWhenRecordDoesNotHaveOne(): void
    {
        $collection = new Collection(new Factory());
        $record1 = new Record();
        $record2 = new Record();
        $collection->insert($record1);
        $collection->insert($record2);

        $this->assertEquals(2, $record2->id);
    }

    public function testShouldIterateOverRecords(): void
    {
        $collection = new Collection(new Factory());
        $collection->insert(new Record());
        $collection->insert(new Record());
        $collection->insert(new Record());
        $count = iterator_to_array($collection);

        $this->assertCount(3, $count);
    }

    public function testShouldReturnAnInstanceOfCollectionWhenFindingRecords(): void
    {
        $factory = $this
            ->getMockBuilder(Factory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $result = new Collection($factory);
        $factory
            ->expects($this->once())
            ->method('collection')
            ->will($this->returnValue($result));

        $collection = new Collection($factory);

        $this->assertSame($collection->findAll(), $result);
    }

    public function testShouldReturnRecordsAccordingToCriteriaWhenFindingRecords(): void
    {
        $collection = new Collection(new Factory());
        $collection->insert(['name' => 'A']);
        $collection->insert(['name' => 'B']);
        $collection->insert(['name' => 'A']);
        $collection->insert(['name' => 'C']);
        $collection->insert(['name' => 'A']);
        $criteria = ['name' => 'A'];
        $result = $collection->findAll($criteria);

        $this->assertCount(3, $result);
    }

    public function testShouldReturnRecordsAccordingToCriteriaAndLimitWhenFindingRecords(): void
    {
        $collection = new Collection(new Factory());
        $collection->insert(['name' => 'A']);
        $collection->insert(['name' => 'B']);
        $collection->insert(['name' => 'A']);
        $collection->insert(['name' => 'C']);
        $collection->insert(['name' => 'A']);
        $criteria = ['name' => 'A'];
        $result = $collection->findAll($criteria, 2);

        $this->assertCount(2, $result);
    }

    public function testShouldReturnRecordAccordingToCriteria(): void
    {
        $record1 = new Record(['name' => 'A']);
        $record2 = new Record(['name' => 'B']);
        $collection = new Collection(new Factory());
        $collection->insert($record1);
        $collection->insert($record2);
        $criteria = ['name' => 'B'];
        $result = $collection->find($criteria);

        $this->assertSame($record2, $result);
    }

    public function testShouldUpdateRecords(): void
    {
        $record1 = new Record(['name' => 'A']);
        $record2 = new Record(['name' => 'B']);
        $collection = new Collection(new Factory());
        $collection->insert($record1);
        $collection->insert($record2);
        $update = ['name' => 'C'];
        $collection->update($update);
        $expectedTrue = true;
        foreach ($collection as $record) {
            $expectedTrue = $expectedTrue && ($record->name == 'C');
        }

        $this->assertTrue($expectedTrue);
    }

    public function testShouldUpdateRecordsAccordingToCriteria(): void
    {
        $record1 = new Record(['name' => 'A']);
        $record2 = new Record(['name' => 'A']);
        $collection = new Collection(new Factory());
        $collection->insert($record1);
        $collection->insert($record2);
        $update = ['name' => 'C'];
        $criteria = ['name' => 'D'];
        $collection->update($update, $criteria);
        $expectedTrue = true;
        foreach ($collection as $record) {
            $expectedTrue = $expectedTrue && ($record->name == 'A');
        }

        $this->assertTrue($expectedTrue);
    }

    public function testShouldDeleteRecords(): void
    {
        $record1 = new Record(['name' => 'A']);
        $record2 = new Record(['name' => 'B']);
        $collection = new Collection(new Factory());
        $collection->insert($record1);
        $collection->insert($record2);
        $collection->delete();

        $this->assertCount(0, $collection);
    }

    public function testShouldDeleteRecordsAccordingToCriteria(): void
    {
        $collection = new Collection(new Factory());
        $collection->insert(['name' => 'A']);
        $collection->insert(['name' => 'B']);
        $collection->insert(['name' => 'C']);
        $criteria = ['name' => 'B'];
        $collection->delete($criteria);

        $this->assertCount(2, $collection);
    }
}
