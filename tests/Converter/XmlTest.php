<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Converter;

use PHPFluent\ArrayStorage\Factory;
use PHPFluent\ArrayStorage\Storage;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers PHPFluent\ArrayStorage\Converter\Xml
 */
class XmlTest extends TestCase
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
        $converter = new Xml();

        $record = $this->factory->record();
        $record->id = 10;
        $record->name = 'Henrique Moody';
        $record->child = $this->factory->record(['id' => 10]);
        $record->ids = [1, 2, 'keyName' => 3];
        $record->other = new stdClass();
        $record->other->foo = true;
        $record->other->bar = 'Some';

        $expectedValue = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Record>
  <id>10</id>
  <name>Henrique Moody</name>
  <child>
    <id>10</id>
  </child>
  <ids>
    <value>1</value>
    <value>2</value>
    <keyName>3</keyName>
  </ids>
  <other>
    <foo>1</foo>
    <bar>Some</bar>
  </other>
</Record>

XML;
        $actualValue = $converter->convert($record);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertCollection(): void
    {
        $converter = new Xml();

        $record1 = $this->factory->record();
        $record2 = $this->factory->record();
        $record3 = $this->factory->record();

        $collection = $this->factory->collection();
        $collection->insert($record1);
        $collection->insert($record2);
        $collection->insert($record3);

        $expectedValue = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Collection>
  <Record>
    <id>1</id>
  </Record>
  <Record>
    <id>2</id>
  </Record>
  <Record>
    <id>3</id>
  </Record>
</Collection>

XML;
        $actualValue = $converter->convert($collection);

        $this->assertSame($expectedValue, $actualValue);
    }

    public function testShouldConvertStorage(): void
    {
        $converter = new Xml();

        $record1 = $this->factory->record();
        $record2 = $this->factory->record();
        $record3 = $this->factory->record();

        $storage = new Storage($this->factory);
        $storage->whatever->insert($record1);
        $storage->whatever->insert($record2);
        $storage->whatever->insert($record3);

        $expectedValue = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Storage>
  <whatever>
    <Record>
      <id>1</id>
    </Record>
    <Record>
      <id>2</id>
    </Record>
    <Record>
      <id>3</id>
    </Record>
  </whatever>
</Storage>

XML;
        $actualValue = $converter->convert($storage);

        $this->assertSame($expectedValue, $actualValue);
    }
}
