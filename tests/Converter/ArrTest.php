<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Converter;

use PHPFluent\ArrayStorage\Factory;
use PHPFluent\ArrayStorage\Storage;
use PHPUnit\Framework\TestCase;

/**
 * @covers PHPFluent\ArrayStorage\Converter\Arr
 */
class ArrTest extends TestCase
{
    /**
     * @var Factory
     */
    protected $factory;

    protected function setUp(): void
    {
        $this->factory = new Factory();
    }

    public function testShouldConvertRecord(): void
    {
        $converter = new Arr(false);

        $record = $this->factory->record();
        $record->id = 10;
        $record->name = 'Henrique Moody';
        $record->child = $this->factory->record(['id' => 10]);

        $expectedValue = [
            'id' => $record->id,
            'name' => $record->name,
            'child' => $record->child,
        ];
        $actualValue = $converter->convert($record);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertRecordRecursively(): void
    {
        $converter = new Arr(true);

        $record = $this->factory->record();
        $record->id = 10;
        $record->name = 'Henrique Moody';
        $record->child = $this->factory->record(['id' => 10]);

        $expectedValue = [
            'id' => $record->id,
            'name' => $record->name,
            'child' => ['id' => $record->child->id],
        ];
        $actualValue = $converter->convert($record);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertCollection(): void
    {
        $converter = new Arr(false);

        $record1 = $this->factory->record();
        $record2 = $this->factory->record();
        $record3 = $this->factory->record();

        $collection = $this->factory->collection();
        $collection->insert($record1);
        $collection->insert($record2);
        $collection->insert($record3);

        $expectedValue = [
            $record1,
            $record2,
            $record3,
        ];
        $actualValue = $converter->convert($collection);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertCollectionRecursively(): void
    {
        $converter = new Arr(true);

        $record1 = $this->factory->record();
        $record2 = $this->factory->record();
        $record3 = $this->factory->record();

        $collection = $this->factory->collection();
        $collection->insert($record1);
        $collection->insert($record2);
        $collection->insert($record3);

        $expectedValue = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ];
        $actualValue = $converter->convert($collection);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertStorage(): void
    {
        $converter = new Arr(false);

        $storage = new Storage($this->factory);
        $storage->whatever->insert([]);

        $expectedValue = [
            'whatever' => $storage->whatever,
        ];
        $actualValue = $converter->convert($storage);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertStorageRecursively(): void
    {
        $converter = new Arr(true);

        $record1 = $this->factory->record();
        $record2 = $this->factory->record();
        $record3 = $this->factory->record();

        $storage = new Storage($this->factory);
        $storage->whatever->insert($record1);
        $storage->whatever->insert($record2);
        $storage->whatever->insert($record3);

        $expectedValue = [
            'whatever' => [
                ['id' => 1],
                ['id' => 2],
                ['id' => 3],
            ],
        ];
        $actualValue = $converter->convert($storage);

        $this->assertSame($expectedValue, $actualValue);
    }
}
