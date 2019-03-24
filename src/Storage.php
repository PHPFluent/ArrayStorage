<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;
use function count;

class Storage implements IteratorAggregate, Countable
{
    /**
     * @var Collection[]
     */
    protected $collections = [];

    /**
     * @var Factory
     */
    protected $factory;

    public function __construct(?Factory $factory = null)
    {
        $this->factory = $factory ?: new Factory();
    }

    public function __get(string $name): Collection
    {
        if (!isset($this->collections[$name])) {
            $this->collections[$name] = $this->factory->collection();
        }

        return $this->collections[$name];
    }

    public function count(): int
    {
        return count($this->collections);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->collections);
    }
}
