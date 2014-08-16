<?php

namespace PHPFluent\ArrayStorage;

/**
 * @covers PHPFluent\ArrayStorage\Storage
 */
class StorageTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldGetCollectionWhenPropertyDoesNoExists()
    {
        $storage = new Storage();
        $collection = $storage->whatever;

        $this->assertInstanceOf(__NAMESPACE__ . '\\Collection', $collection);
    }

    public function testShouldCountCollections()
    {
        $storage = new Storage();
        $storage->foo;
        $storage->bar;

        $this->assertCount(2, $storage);
    }

    public function testShouldIterateOverCollections()
    {
        $storage = new Storage();
        $storage->foo;
        $storage->bar;

        $count = 0;
        foreach ($storage as $key => $collection) {
            $count++;
        }

        $this->assertEquals(2, $count);
    }
}
