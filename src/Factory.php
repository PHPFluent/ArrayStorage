<?php

namespace PHPFluent\ArrayStorage;

use InvalidArgumentException;
use PHPFluent\ArrayStorage\Filter\EqualTo;
use PHPFluent\ArrayStorage\Filter\Filter;
use PHPFluent\ArrayStorage\Filter\Not;
use PHPFluent\ArrayStorage\Filter\OneOf;
use ReflectionClass;

class Factory
{
    public function collection($collection = null)
    {
        if (! $collection instanceof Collection) {
            $collection = new Collection($this);
        }

        return $collection;
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
            $criteria = new Criteria($this);
            foreach ($filters as $key => $value) {
                if ($value instanceof Filter) {
                    $criteria->addFilter($key, $value);
                    continue;
                }

                $criteria->addFilter($key, new EqualTo($value));
            }
        }

        return $criteria;
    }

    public function filter($filter, array $arguments = array())
    {
        if (! $filter instanceof Filter) {
            if (0 === strpos($filter, 'not')) {
                $filterName = substr($filter, 3);
                $filter = $this->filter($filterName, $arguments);

                return new Not($filter);
            }

            if (false !== strpos($filter, 'Or')) {
                $filtersNames = explode('Or', $filter);
                $filters = array();
                foreach ($filtersNames as $filterName) {
                    $filters[] = $this->filter($filterName, $arguments);
                }

                return new OneOf($filters);
            }

            $reflectionClass = new ReflectionClass(__NAMESPACE__ . '\\Filter\\' . ucfirst($filter));
            if (! $reflectionClass->isSubclassOf(__NAMESPACE__ . '\\Filter\\Filter')) {
                throw new InvalidArgumentException(sprintf('"%s" is not a valid filter name', $filter));
            }

            return $reflectionClass->newInstanceArgs($arguments);
        }

        return $filter;
    }
}
