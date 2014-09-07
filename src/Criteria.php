<?php

namespace PHPFluent\ArrayStorage;

use Countable;
use PHPFluent\ArrayStorage\Filter\Filter;
use UnexpectedValueException;

class Criteria implements Countable, Filter
{
    protected $currentIndex = null;
    protected $factory;
    protected $filters = array();

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
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

        $filter = $this->factory->filter($methodName, $arguments);

        $this->addFilter($this->currentIndex, $filter);

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
