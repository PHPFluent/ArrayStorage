<?php

namespace PHPFluent\ArrayStorage;

use PHPFluent\ArrayStorage\Filter\Filter;
use PHPFluent\ArrayStorage\Filter\EqualTo;

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
            $criteria = new Criteria();
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
}
