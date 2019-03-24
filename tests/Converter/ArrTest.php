<?php

namespace PHPFluent\ArrayStorage\Converter;

use PHPFluent\ArrayStorage\Factory;
use PHPFluent\ArrayStorage\Storage;

/**
 * @covers PHPFluent\ArrayStorage\Converter\Arr
 */
class ArrTest extends \PHPUnit\Framework\TestCase
{
    protected $factory;

    protected function setUp(): void
    {
        $this->factory = new Factory();
    }

    public function testShouldConvertRecord()
    {
        $converter = new Arr(false);

        $record = $this->factory->record();
        $record->id = 10;
        $record->name = 'Henrique Moody';
        $record->child = $this->factory->record(array('id' => 10));

        $expectedValue = array(
            'id' => $record->id,
            'name' => $record->name,
            'child' => $record->child,
        );
        $actualValue = $converter->convert($record);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertRecordRecursively()
    {
        $converter = new Arr(true);

        $record = $this->factory->record();
        $record->id = 10;
        $record->name = 'Henrique Moody';
        $record->child = $this->factory->record(array('id' => 10));

        $expectedValue = array(
            'id' => $record->id,
            'name' => $record->name,
            'child' => array('id' => $record->child->id),
        );
        $actualValue = $converter->convert($record);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertCollection()
    {
        $converter = new Arr(false);

        $record1 = $this->factory->record();
        $record2 = $this->factory->record();
        $record3 = $this->factory->record();

        $collection = $this->factory->collection();
        $collection->insert($record1);
        $collection->insert($record2);
        $collection->insert($record3);

        $expectedValue = array(
            $record1,
            $record2,
            $record3,
        );
        $actualValue = $converter->convert($collection);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertCollectionRecursively()
    {
        $converter = new Arr(true);

        $record1 = $this->factory->record();
        $record2 = $this->factory->record();
        $record3 = $this->factory->record();

        $collection = $this->factory->collection();
        $collection->insert($record1);
        $collection->insert($record2);
        $collection->insert($record3);

        $expectedValue = array(
            array('id' => 1),
            array('id' => 2),
            array('id' => 3),
        );
        $actualValue = $converter->convert($collection);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertStorage()
    {
        $converter = new Arr(false);

        $storage = new Storage($this->factory);
        $storage->whatever->insert(array());

        $expectedValue = array(
            'whatever' => $storage->whatever,
        );
        $actualValue = $converter->convert($storage);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertStorageRecursively()
    {
        $converter = new Arr(true);

        $record1 = $this->factory->record();
        $record2 = $this->factory->record();
        $record3 = $this->factory->record();

        $storage = new Storage($this->factory);
        $storage->whatever->insert($record1);
        $storage->whatever->insert($record2);
        $storage->whatever->insert($record3);

        $expectedValue = array(
            'whatever' => array(
                array('id' => 1),
                array('id' => 2),
                array('id' => 3),
            )
        );
        $actualValue = $converter->convert($storage);

        $this->assertSame($expectedValue, $actualValue);
    }
}
