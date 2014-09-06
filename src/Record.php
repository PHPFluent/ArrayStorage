<?php

namespace PHPFluent\ArrayStorage;

use ArrayIterator;
use IteratorAggregate;

class Record implements IteratorAggregate
{
    protected $data = array('id' => null);

    public function __construct(array $data = array())
    {
        foreach ($data as $name => $value) {
            $this->__set($name, $value);
        }
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (! isset($this->data[$name])) {
            $this->data[$name] = null;
        }

        return $this->data[$name];
    }

    public function __toString()
    {
        return (string) $this->__get('id');
    }

    public function update(array $update)
    {
        foreach ($update as $key => $value) {
            $this->__set($key, $value);
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }
}
