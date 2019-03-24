<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage;

use Countable;
use PHPFluent\ArrayStorage\Filter\Filter;
use UnexpectedValueException;
use function count;

class Criteria implements Countable, Filter
{
    /**
     * @var string|null
     */
    protected $currentIndex = null;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var Filter[]
     */
    protected $filters = [];

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    public function addFilter(string $index, Filter $filter): void
    {
        $this->filters[] = [$index, $filter];
    }

    public function count(): int
    {
        return count($this->filters);
    }

    public function __get(string $index): self
    {
        $this->currentIndex = $index;

        return $this;
    }

    /**
     * @param mixed[] $arguments
     */
    public function __call(string $methodName, array $arguments = []): self
    {
        if ($this->currentIndex === null) {
            throw new UnexpectedValueException('You first need to call a property for this filter');
        }

        $filter = $this->factory->filter($methodName, $arguments);

        $this->addFilter($this->currentIndex, $filter);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($record): bool
    {
        $record = ($record instanceof Record ? $record : new Record($record));
        foreach ($this->filters as $value) {
            [$index, $filter] = $value;
            $value = $record->__get($index);
            if ($filter->isValid($value)) {
                continue;
            }

            return false;
        }

        return true;
    }
}
