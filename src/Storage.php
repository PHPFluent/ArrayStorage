<?php

namespace PHPFluent\ArrayStorage;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class Storage implements IteratorAggregate, Countable
{
    protected $collections = array();

    public function __get($name)
    {
        if (! isset($this->collections[$name])) {
            $this->collections[$name] = new Collection();
        }

        return $this->collections[$name];
    }

    public function count()
    {
        return count($this->collections);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->collections);
    }
}
