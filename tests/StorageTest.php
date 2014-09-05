<?php

namespace PHPFluent\ArrayStorage;

/**
 * @covers PHPFluent\ArrayStorage\Storage
 */
class StorageTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldHaveAnInstanceOfFactoryByDefault()
    {
        $storage = new Storage();

        $this->assertAttributeInstanceOf('PHPFluent\\ArrayStorage\\Factory', 'factory', $storage);
    }

    public function testShouldAcceptAnInstanceOfFactoryOnConstructor()
    {
        $factory = $this
            ->getMockBuilder('PHPFluent\\ArrayStorage\\Factory')
            ->disableOriginalConstructor()
            ->getMock();

        $storage = new Storage($factory);

        $this->assertAttributeSame($factory, 'factory', $storage);
    }

    public function testShouldGetCollectionWhenPropertyDoesNoExists()
    {
        $storage = new Storage();
        $collection = $storage->whatever;

        $this->assertInstanceOf(__NAMESPACE__ . '\\Collection', $collection);
    }

    public function testShouldUseFactoryToCreateCollections()
    {
        $factory = $this
            ->getMockBuilder('PHPFluent\\ArrayStorage\\Factory')
            ->disableOriginalConstructor()
            ->getMock();
        $factory
            ->expects($this->once())
            ->method('collection');

        $storage = new Storage($factory);
        $storage->whatever;
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
