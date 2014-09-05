<?php

namespace PHPFluent\ArrayStorage;

/**
 * @covers PHPFluent\ArrayStorage\Collection
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldAcceptAnInstanceOfFactoryOnConstructor()
    {
        $factory = new Factory();
        $collection = new Collection($factory);

        $this->assertAttributeSame($factory, 'factory', $collection);
    }

    public function testShouldUseFactoryToCreateRecords()
    {
        $data = array('foo' => true);
        $record = new Record($data);

        $factory = $this
            ->getMockBuilder('PHPFluent\\ArrayStorage\\Factory')
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

    public function testShouldUseFactoryToCreateCriteria()
    {
        $factory = $this
            ->getMockBuilder('PHPFluent\\ArrayStorage\\Factory')
            ->disableOriginalConstructor()
            ->getMock();

        $filters = array('foo' => $this->getMock('PHPFluent\\ArrayStorage\\Filter\\Filter'));

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

    public function testShouldCountRecordsInCollection()
    {
        $collection = new Collection(new Factory());

        $this->assertCount(0, $collection);
    }

    public function testShouldInsertRecordToCollection()
    {
        $collection = new Collection(new Factory());
        $record = new Record(array('id' => 1, 'name' => 'Jéssica Santana'));
        $collection->insert($record);

        $this->assertCount(1, $collection);
    }

    public function testShouldInsertRecordToCollectionAndCreateIdWhenRecordDoesNotHaveOne()
    {
        $collection = new Collection(new Factory());
        $record1 = new Record();
        $record2 = new Record();
        $collection->insert($record1);
        $collection->insert($record2);

        $this->assertEquals(2, $record2->id);
    }

    public function testShouldIterateOverRecords()
    {
        $collection = new Collection(new Factory());
        $collection->insert(new Record());
        $collection->insert(new Record());
        $collection->insert(new Record());
        $count = 0;
        foreach ($collection as $record) {
            $count++;
        }

        $this->assertEquals(3, $count);
    }

    public function testShouldReturnAnInstanceOfCollectionWhenFindingRecords()
    {
        $factory = $this
            ->getMockBuilder('PHPFluent\\ArrayStorage\\Factory')
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

    public function testShouldReturnRecordsAccordingToCriteriaWhenFindingRecords()
    {
        $collection = new Collection(new Factory());
        $collection->insert(array('name' => 'A'));
        $collection->insert(array('name' => 'B'));
        $collection->insert(array('name' => 'A'));
        $collection->insert(array('name' => 'C'));
        $collection->insert(array('name' => 'A'));
        $criteria = array('name' => 'A');
        $result = $collection->findAll($criteria);

        $this->assertCount(3, $result);
    }

    public function testShouldReturnRecordsAccordingToCriteriaAndLimitWhenFindingRecords()
    {
        $collection = new Collection(new Factory());
        $collection->insert(array('name' => 'A'));
        $collection->insert(array('name' => 'B'));
        $collection->insert(array('name' => 'A'));
        $collection->insert(array('name' => 'C'));
        $collection->insert(array('name' => 'A'));
        $criteria = array('name' => 'A');
        $result = $collection->findAll($criteria, 2);

        $this->assertCount(2, $result);
    }

    public function testShouldReturnRecordAccordingToCriteria()
    {
        $record1 = new Record(array('name' => 'A'));
        $record2 = new Record(array('name' => 'B'));
        $collection = new Collection(new Factory());
        $collection->insert($record1);
        $collection->insert($record2);
        $criteria = array('name' => 'B');
        $result = $collection->find($criteria);

        $this->assertSame($record2, $result);
    }

    public function testShouldUpdateRecords()
    {
        $record1 = new Record(array('name' => 'A'));
        $record2 = new Record(array('name' => 'B'));
        $collection = new Collection(new Factory());
        $collection->insert($record1);
        $collection->insert($record2);
        $update = array('name' => 'C');
        $result = $collection->update($update);
        $expectedTrue = true;
        foreach ($collection as $record) {
            $expectedTrue = $expectedTrue && ($record->name == 'C');
        }

        $this->assertTrue($expectedTrue);
    }

    public function testShouldUpdateRecordsAccordingToCriteria()
    {
        $record1 = new Record(array('name' => 'A'));
        $record2 = new Record(array('name' => 'A'));
        $collection = new Collection(new Factory());
        $collection->insert($record1);
        $collection->insert($record2);
        $update = array('name' => 'C');
        $criteria = array('name' => 'D');
        $result = $collection->update($update, $criteria);
        $expectedTrue = true;
        foreach ($collection as $record) {
            $expectedTrue = $expectedTrue && ($record->name == 'A');
        }

        $this->assertTrue($expectedTrue);
    }

    public function testShouldDeleteRecords()
    {
        $record1 = new Record(array('name' => 'A'));
        $record2 = new Record(array('name' => 'B'));
        $collection = new Collection(new Factory());
        $collection->insert($record1);
        $collection->insert($record2);
        $collection->delete();

        $this->assertCount(0, $collection);
    }

    public function testShouldDeleteRecordsAccordingToCriteria()
    {
        $collection = new Collection(new Factory());
        $collection->insert(array('name' => 'A'));
        $collection->insert(array('name' => 'B'));
        $collection->insert(array('name' => 'C'));
        $criteria = array('name' => 'B');
        $collection->delete($criteria);

        $this->assertCount(2, $collection);
    }

    public function testShouldConvertCollectionToArray()
    {
        $record1 = new Record(array('name' => 'A', 'child' => new Record(array('id' => 13))));
        $record2 = new Record(array('name' => 'B', 'child' => new Record(array('id' => 42))));
        $collection = new Collection(new Factory());
        $collection->insert($record1);
        $collection->insert($record2);
        $expectedArray = array(
            array('id' => 1, 'name' => 'A', 'child' => 13),
            array('id' => 2, 'name' => 'B', 'child' => 42),
        );

        $this->assertSame($expectedArray, $collection->toArray());
    }

    public function testShouldConvertCollectionToExpandedeArray()
    {
        $record1 = new Record(array('name' => 'A', 'child' => new Record(array('id' => 13))));
        $record2 = new Record(array('name' => 'B', 'child' => new Record(array('id' => 42))));
        $collection = new Collection(new Factory());
        $collection->insert($record1);
        $collection->insert($record2);
        $expectedArray = array(
            array('id' => 1, 'name' => 'A', 'child' => array('id' => 13)),
            array('id' => 2, 'name' => 'B', 'child' => array('id' => 42)),
        );

        $this->assertSame($expectedArray, $collection->toArray(true));
    }
}
