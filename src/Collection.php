<?php

namespace PHPFluent\ArrayStorage;

use ArrayIterator;
use IteratorAggregate;
use Countable;

class Collection implements Countable, IteratorAggregate
{
    protected $name;
    protected $records = array();
    protected $lastRecordId = 0;

    public function __construct($name, array $records = array())
    {
        $this->name = $name;
        foreach ($records as $record) {
            $this->insert($record);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function record($record)
    {
        if (! $record instanceof Record) {
            $data = (array) $record;
            $record = new Record($data);
        }

        return $record;
    }

    public function count()
    {
        return count($this->records);
    }

    public function insert($data)
    {
        $record = $this->record($data);
        if (0 >= $record->id) {
            $record->id = ++$this->lastRecordId;
        }

        $this->records[] = $record;

        return $record->id;
    }

    public function update(array $update, array $criteria = array())
    {
        $records = $this->findAll($criteria);
        foreach ($records as $record) {
            $record->update($update);
        }
    }

    public function delete(array $criteria = array())
    {
        foreach ($this->records as $key => $record) {
            if (! $this->equals($criteria, $record)) {
                continue;
            }

            unset($this->records[$key]);
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->records);
    }

    protected function equals(array $values, Record $record)
    {
        foreach ($values as $key => $value) {
            if ((string) $record->__get($key) == (string) $value) {
                continue;
            }

            return false;
        }

        return true;
    }

    public function findAll(array $criteria = array(), $limit = null)
    {
        $count = 0;
        $results = array();
        foreach ($this->records as $record) {
            if (null !== $limit && $count == $limit) {
                continue;
            }

            if (! $this->equals($criteria, $record)) {
                continue;
            }

            $count++;
            $results[] = $record;
        }

        $name = $this->name . '-' . json_encode($criteria);

        return new static($name, $results);
    }

    public function find(array $criteria = array())
    {
        $records = $this->findAll($criteria, 1);

        return $records->getIterator()->current();
    }

    public function toArray($expandChildren = false)
    {
        $results = array();
        foreach ($this->records as $record) {
            $results[] = $record->toArray($expandChildren);
        }

        return $results;
    }
}
