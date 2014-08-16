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

    public function testShouldConvertToArray()
    {
        $record = new Record();
        $record->id = 42;
        $record->name = 'Henrique Moody';
        $record->email = 'henriquemoody@gmail.com';

        $expectedArray = array('id' => 42, 'name' => 'Henrique Moody', 'email' => 'henriquemoody@gmail.com');

        $this->assertSame($expectedArray, $record->toArray());
    }

    public function testShouldConvertToArrayEvenWhenHasARecordAsChild()
    {
        $child = new Record();
        $child->id = 42;

        $parent = new Record();
        $parent->child = $child;

        $expectedArray = array(
            'id' => null,
            'child' => 42,
        );

        $this->assertSame($expectedArray, $parent->toArray());
    }

    public function testShouldConvertToExpandedArrayEvenWhenHasARecordAsChild()
    {
        $child = new Record();
        $child->id = 42;
        $child->name = 'Henrique Moody';
        $child->email = 'henriquemoody@gmail.com';

        $parent = new Record();
        $parent->child = $child;

        $expectedArray = array(
            'id' => null,
            'child' => array('id' => 42, 'name' => 'Henrique Moody', 'email' => 'henriquemoody@gmail.com'),
        );

        $this->assertSame($expectedArray, $parent->toArray(true));
    }
}
