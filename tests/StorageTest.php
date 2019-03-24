<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage;

use PHPUnit\Framework\TestCase;
use function count;
use function iterator_to_array;

/**
 * @covers PHPFluent\ArrayStorage\Storage
 */
class StorageTest extends TestCase
{
    public function testShouldGetCollectionWhenPropertyDoesNoExists(): void
    {
        $storage = new Storage();
        $collection = $storage->whatever;

        $this->assertInstanceOf(__NAMESPACE__.'\\Collection', $collection);
    }

    public function testShouldUseFactoryToCreateCollections(): void
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

    public function testShouldCountCollections(): void
    {
        $storage = new Storage();
        $storage->foo;
        $storage->bar;

        $this->assertCount(2, $storage);
    }

    public function testShouldIterateOverCollections(): void
    {
        $storage = new Storage();
        $storage->foo;
        $storage->bar;

        $count = count(iterator_to_array($storage));

        $this->assertEquals(2, $count);
    }
}
