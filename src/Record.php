<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Record implements IteratorAggregate
{
    /**
     * @var mixed[]
     */
    protected $data = ['id' => null];

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $name => $value) {
            $this->__set($name, $value);
        }
    }

    /**
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    /**
     * @return mixed
     */
    public function __get(string $name)
    {
        if (!isset($this->data[$name])) {
            $this->data[$name] = null;
        }

        return $this->data[$name];
    }

    public function __toString(): string
    {
        return (string) $this->__get('id');
    }

    /**
     * @param mixed[] $update
     */
    public function update(array $update): void
    {
        foreach ($update as $key => $value) {
            $this->__set($key, $value);
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }
}
