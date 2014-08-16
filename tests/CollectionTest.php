<?php

namespace PHPFluent\ArrayStorage;

/**
 * @covers PHPFluent\ArrayStorage\Collection
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldDefineNameOnConstructor()
    {
        $name = 'whatever';
        $collection = new Collection($name);

        $this->assertEquals($name, $collection->getName());
    }

    public function testShouldCreateNewRecord()
    {
        $collection = new Collection('whatever');
        $record = $collection->record();

        $this->assertInstanceOf(__NAMESPACE__ . '\\Record', $record);
    }

    public function testShouldCreateNewRecordFromArray()
    {
        $collection = new Collection('whatever');
        $data = array();
        $record = $collection->record($data);

        $this->assertInstanceOf(__NAMESPACE__ . '\\Record', $record);
    }

    public function testShouldCreateNewRecordFromStdClass()
    {
        $collection = new Collection('whatever');
        $data = new \stdClass();
        $record = $collection->record($data);

        $this->assertInstanceOf(__NAMESPACE__ . '\\Record', $record);
    }

    public function testShouldReturnTheSameInstanceWhenDataIsAlreadyARecordInstance()
    {
        $collection = new Collection('whatever');
        $data = new Record();
        $record = $collection->record($data);

        $this->assertSame($data, $record);
    }

    public function testShouldCountRecordsInCollection()
    {
        $collection = new Collection('whatever');

        $this->assertCount(0, $collection);
    }

    public function testShouldInsertRecordToCollection()
    {
        $collection = new Collection('whatever');
        $record = new Record(array('id' => 1, 'name' => 'Jéssica Santana'));
        $collection->insert($record);

        $this->assertCount(1, $collection);
    }

    public function testShouldInsertRecordToCollectionAndCreateIdWhenRecordDoesNotHaveOne()
    {
        $collection = new Collection('whatever');
        $record1 = new Record();
        $record2 = new Record();
        $collection->insert($record1);
        $collection->insert($record2);

        $this->assertEquals(2, $record2->id);
    }

    public function testShouldInsertRecordsOnConstructor()
    {
        $records = array(
            new Record(),
            new Record(),
        );
        $collection = new Collection('whatever', $records);

        $this->assertCount(2, $collection);
    }

    public function testShouldIterateOverRecords()
    {
        $records = array(
            new Record(),
            new Record(),
            new Record(),
        );
        $collection = new Collection('whatever', $records);
        $count = 0;
        foreach ($collection as $record) {
            $count++;
        }

        $this->assertEquals(3, $count);
    }

    public function testShouldReturnAnInstanceOfCollectionWhenFindingRecords()
    {
        $collection = new Collection('whatever');
        $result = $collection->findAll();

        $this->assertInstanceOf(__NAMESPACE__ . '\\Collection', $result);
    }

    public function testShouldReturnACollectionWithANameBasedOnCriteriaWhenFindingRecords()
    {
        $collection = new Collection('whatever');
        $criteria = array('foo' => true, 'bar' => 2, 'baz' => 'B');
        $result = $collection->findAll($criteria);
        $expectedName = 'whatever-{"foo":true,"bar":2,"baz":"B"}';

        $this->assertEquals($expectedName, $result->getName());
    }

    public function testShouldReturnRecordsAccordingToCriteriaWhenFindingRecords()
    {
        $records = array(
            array('name' => 'A'),
            array('name' => 'B'),
            array('name' => 'A'),
            array('name' => 'C'),
            array('name' => 'A'),
        );
        $collection = new Collection('whatever', $records);
        $criteria = array('name' => 'A');
        $result = $collection->findAll($criteria);

        $this->assertCount(3, $result);
    }

    public function testShouldReturnRecordsAccordingToCriteriaAndLimitWhenFindingRecords()
    {
        $records = array(
            array('name' => 'A'),
            array('name' => 'B'),
            array('name' => 'A'),
            array('name' => 'C'),
            array('name' => 'A'),
        );
        $collection = new Collection('whatever', $records);
        $criteria = array('name' => 'A');
        $result = $collection->findAll($criteria, 2);

        $this->assertCount(2, $result);
    }

    public function testShouldReturnRecordAccordingToCriteria()
    {
        $record1 = new Record(array('name' => 'A'));
        $record2 = new Record(array('name' => 'B'));
        $records = array(
            $record1,
            $record2,
        );
        $collection = new Collection('whatever', $records);
        $criteria = array('name' => 'B');
        $result = $collection->find($criteria);

        $this->assertSame($record2, $result);
    }

    public function testShouldUpdateRecords()
    {
        $record1 = new Record(array('name' => 'A'));
        $record2 = new Record(array('name' => 'B'));
        $records = array(
            $record1,
            $record2,
        );
        $collection = new Collection('whatever', $records);
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
        $records = array(
            $record1,
            $record2,
        );
        $collection = new Collection('whatever', $records);
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
        $records = array(
            $record1,
            $record2,
        );
        $collection = new Collection('whatever', $records);
        $collection->delete();

        $this->assertCount(0, $collection);
    }

    public function testShouldDeleteRecordsAccordingToCriteria()
    {
        $records = array(
            array('name' => 'A'),
            array('name' => 'B'),
            array('name' => 'C'),
        );
        $collection = new Collection('whatever', $records);
        $criteria = array('name' => 'B');
        $collection->delete($criteria);

        $this->assertCount(2, $collection);
    }

    public function testShouldConvertCollectionToArray()
    {
        $record1 = new Record(array('name' => 'A', 'child' => new Record(array('id' => 13))));
        $record2 = new Record(array('name' => 'B', 'child' => new Record(array('id' => 42))));
        $records = array(
            $record1,
            $record2,
        );
        $collection = new Collection('whatever', $records);
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
        $records = array(
            $record1,
            $record2,
        );
        $collection = new Collection('whatever', $records);
        $expectedArray = array(
            array('id' => 1, 'name' => 'A', 'child' => array('id' => 13)),
            array('id' => 2, 'name' => 'B', 'child' => array('id' => 42)),
        );

        $this->assertSame($expectedArray, $collection->toArray(true));
    }
}
