<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage;

use PHPUnit\Framework\TestCase;
use function iterator_to_array;

/**
 * @covers PHPFluent\ArrayStorage\Record
 */
class RecordTest extends TestCase
{
    public function testShouldDefineAndReturnPropertyDynamically(): void
    {
        $record = new Record();
        $record->name = 'Henrique Moody';

        $this->assertEquals('Henrique Moody', $record->name);
    }

    public function testShouldDefineDataOnConstructor(): void
    {
        $record = new Record(['name' => 'Henrique Moody']);

        $this->assertEquals('Henrique Moody', $record->name);
    }

    public function testShouldReturnNullIfPropertyDoesNotExists(): void
    {
        $record = new Record();

        $this->assertNull($record->name);
    }

    public function testShouldReturnValueOfKeyIdWhenConvertingToString(): void
    {
        $record = new Record();
        $record->id = 42;

        $this->assertEquals('42', (string) $record);
    }

    public function testShouldUpdateDateFromArray(): void
    {
        $update = [
            'name' => 'Jéssica Santana',
        ];
        $record = new Record();
        $record->name = 'Henrique Moody';
        $record->update($update);

        $this->assertEquals($update['name'], $record->name);
    }

    public function testShouldIterateOverRecord(): void
    {
        $record = new Record();
        $record->id = 1;
        $record->name = 'Henrique Moody';

        $this->assertCount(2, iterator_to_array($record));
    }
}
