<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;
use function count;

class Collection implements Countable, IteratorAggregate
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var Record[]
     */
    protected $records = [];

    /**
     * @var int
     */
    protected $lastRecordId = 0;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Record|mixed[] $record
     */
    public function record($record = null): Record
    {
        return $this->factory->record($record);
    }

    /**
     * @param Criteria|mixed[] $criteria
     */
    public function criteria($criteria = null): Criteria
    {
        return $this->factory->criteria($criteria);
    }

    public function count(): int
    {
        return count($this->records);
    }

    /**
     * @param Record|mixed[] $data
     */
    public function insert($data): int
    {
        $record = $this->record($data);
        if (0 >= $record->id) {
            $record->id = ++$this->lastRecordId;
        }

        $this->records[] = $record;

        return $record->id;
    }

    /**
     * @param mixed[] $update
     * @param Criteria|mixed[] $criteria
     */
    public function update(array $update, $criteria = null): void
    {
        $records = $this->findAll($criteria);
        foreach ($records as $record) {
            $record->update($update);
        }
    }

    /**
     * @param Criteria|mixed[] $criteria
     */
    public function delete($criteria = null): void
    {
        $criteria = $this->criteria($criteria);
        foreach ($this->records as $key => $record) {
            if (!$criteria->isValid($record)) {
                continue;
            }

            unset($this->records[$key]);
        }
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->records);
    }

    /**
     * @param Criteria|mixed[] $criteria
     */
    public function findAll($criteria = null, ?int $limit = null): Collection
    {
        $count = 0;
        $criteria = $this->criteria($criteria);
        $collection = $this->factory->collection();
        foreach ($this->records as $record) {
            if ($limit !== null && $count == $limit) {
                continue;
            }

            if (!$criteria->isValid($record)) {
                continue;
            }

            $count++;
            $collection->insert($record);
        }

        return $collection;
    }

    /**
     * @param Criteria|mixed[] $criteria
     */
    public function find($criteria = null): ?Record
    {
        $records = $this->findAll($criteria, 1);

        return $records->getIterator()->current();
    }
}
