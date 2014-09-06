<?php

namespace PHPFluent\ArrayStorage;

/**
 * @covers PHPFluent\ArrayStorage\Record
 */
class RecordTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldDefineAndReturnPropertyDynamically()
    {
        $record = new Record();
        $record->name = 'Henrique Moody';

        $this->assertEquals('Henrique Moody', $record->name);
    }

    public function testShouldDefineDataOnConstructor()
    {
        $record = new Record(array('name' => 'Henrique Moody'));

        $this->assertEquals('Henrique Moody', $record->name);
    }

    public function testShouldReturnNullIfPropertyDoesNotExists()
    {
        $record = new Record();

        $this->assertNull($record->name);
    }

    public function testShouldReturnValueOfKeyIdWhenConvertingToString()
    {
        $record = new Record();
        $record->id = 42;

        $this->assertEquals('42', (string) $record);
    }

    public function testShouldUpdateDateFromArray()
    {
        $update = array(
            'name' => 'Jéssica Santana'
        );
        $record = new Record();
        $record->name = 'Henrique Moody';
        $record->update($update);

        $this->assertEquals($update['name'], $record->name);
    }

    public function testShouldIterateOverRecord()
    {
        $record = new Record();
        $record->id = 1;
        $record->name = 'Henrique Moody';

        $count = 0;
        foreach ($record as $name => $value) {
            $count++;
        }

        $this->assertEquals(2, $count);
    }
}
