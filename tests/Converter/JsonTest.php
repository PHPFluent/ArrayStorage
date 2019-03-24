<?php

namespace PHPFluent\ArrayStorage\Converter;

use ArrayIterator;
use PHPFluent\ArrayStorage\Factory;
use PHPFluent\ArrayStorage\Storage;

/**
 * @covers PHPFluent\ArrayStorage\Converter\Json
 */
class JsonTest extends \PHPUnit\Framework\TestCase
{
    protected $factory;

    protected function setUp(): void
    {
        $this->factory = new Factory();
    }

    public function testShouldUseDefinedJsonEncodeFlags()
    {
        $converter = new Json(JSON_NUMERIC_CHECK);
        $traversable = new ArrayIterator(array('foo' => '1'));

        $expectedValue = '{"foo":1}';
        $actualValue = $converter->convert($traversable);

        $this->assertEquals($expectedValue, $actualValue);
    }

    public function testShouldUseDefinedArrConverterInstance()
    {
        $traversable = new ArrayIterator(array('foo' => '1'));

        $arrayConverter = $this->createMock(__NAMESPACE__ . '\\Arr');
        $arrayConverter
            ->expects($this->once())
            ->method('convert')
            ->with($traversable)
            ->will($this->returnValue(array('foo' => '1')));
        $converter = new Json(JSON_NUMERIC_CHECK, $arrayConverter);

        $converter->convert($traversable);
    }

    public function testShouldConvertRecord()
    {
        $converter = new Json();

        $record = $this->factory->record();
        $record->id = 10;
        $record->name = 'Henrique Moody';
        $record->child = $this->factory->record(array('id' => 10));

        $expectedValue = <<<JSON
{
    "id": 10,
    "name": "Henrique Moody",
    "child": {
        "id": 10
    }
}
JSON;
        $actualValue = $converter->convert($record);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertCollection()
    {
        $converter = new Json();

        $record1 = $this->factory->record();
        $record2 = $this->factory->record();
        $record3 = $this->factory->record();

        $collection = $this->factory->collection();
        $collection->insert($record1);
        $collection->insert($record2);
        $collection->insert($record3);

        $expectedValue = <<<JSON
[
    {
        "id": 1
    },
    {
        "id": 2
    },
    {
        "id": 3
    }
]
JSON;
        $actualValue = $converter->convert($collection);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertStorage()
    {
        $converter = new Json();

        $record1 = $this->factory->record();
        $record2 = $this->factory->record();
        $record3 = $this->factory->record();

        $storage = new Storage($this->factory);
        $storage->whatever->insert($record1);
        $storage->whatever->insert($record2);
        $storage->whatever->insert($record3);

        $expectedValue = <<<JSON
{
    "whatever": [
        {
            "id": 1
        },
        {
            "id": 2
        },
        {
            "id": 3
        }
    ]
}
JSON;
        $actualValue = $converter->convert($storage);

        $this->assertSame($expectedValue, $actualValue);
    }
}
