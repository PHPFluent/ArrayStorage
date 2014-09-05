<?php

namespace PHPFluent\ArrayStorage;

use ArrayIterator;
use IteratorAggregate;
use Countable;

class Collection implements Countable, IteratorAggregate
{
    protected $factory;
    protected $records = array();
    protected $lastRecordId = 0;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    public function record($record = null)
    {
        if (! $record instanceof Record) {
            $data = (array) $record;
            $record = new Record($data);
        }

        return $record;
    }

    public function criteria($criteria = null)
    {
        if (! $criteria instanceof Criteria) {
            $filters = (array) $criteria;
            $criteria = new Criteria($filters);
        }

        return $criteria;
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

    public function update(array $update, $criteria = null)
    {
        $records = $this->findAll($criteria);
        foreach ($records as $record) {
            $record->update($update);
        }
    }

    public function delete($criteria = null)
    {
        $criteria = $this->criteria($criteria);
        foreach ($this->records as $key => $record) {
            if (! $criteria->isValid($record)) {
                continue;
            }

            unset($this->records[$key]);
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->records);
    }

    public function findAll($criteria = null, $limit = null)
    {
        $count = 0;
        $criteria = $this->criteria($criteria);
        $collection = $this->factory->collection();
        foreach ($this->records as $record) {
            if (null !== $limit && $count == $limit) {
                continue;
            }

            if (! $criteria->isValid($record)) {
                continue;
            }

            $count++;
            $collection->insert($record);
        }

        return $collection;
    }

    public function find($criteria = null)
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
