<?php

namespace PHPFluent\ArrayStorage;

use BadMethodCallException;
use Countable;
use PHPFluent\ArrayStorage\Filter\EqualTo;
use PHPFluent\ArrayStorage\Filter\Filter;
use ReflectionClass;
use UnexpectedValueException;

class Criteria implements Countable, Filter
{
    protected $currentIndex = null;
    protected $filters = array();

    public function __construct(array $filters = array())
    {
        foreach ($filters as $index => $value) {
            if ($value instanceof Filter) {
                $this->addFilter($index, $value);
                continue;
            }

            $this->addFilter($index, new EqualTo($value));
        }
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function addFilter($index, Filter $filter)
    {
        $this->filters[] = array($index, $filter);
    }

    public function count()
    {
        return count($this->filters);
    }

    public function __get($index)
    {
        $this->currentIndex = $index;

        return $this;
    }

    public function __call($methodName, array $arguments = array())
    {
        if (null === $this->currentIndex) {
            throw new UnexpectedValueException('You first need to call a property for this filter');
        }

        $reflection = new ReflectionClass('PHPFluent\\ArrayStorage\\Filter\\' . ucfirst($methodName));
        if (! $reflection->isSubclassOf('PHPFluent\\ArrayStorage\\Filter\\Filter')) {
            throw new BadMethodCallException(sprintf('"%s" is not a valid filter name', $methodName));
        }

        $index = $this->currentIndex;
        $filter = $reflection->newInstanceArgs($arguments);

        $this->addFilter($index, $filter);

        return $this;
    }

    public function isValid($record)
    {
        $record = ($record instanceof Record ? $record : new Record($record));
        foreach ($this->filters as $value) {
            list($index, $filter) = $value;
            $value = $record->__get($index);
            if ($filter->isValid($value)) {
                continue;
            }

            return false;
        }

        return true;
    }
}
