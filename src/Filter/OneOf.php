<?php

namespace PHPFluent\ArrayStorage\Filter;

use InvalidArgumentException;

class OneOf implements Filter
{
    protected $filters = array();

    public function __construct(array $filters)
    {
        foreach ($filters as $filter) {
            if (! $filter instanceof Filter) {
                throw new InvalidArgumentException('Filter is not valid');
            }
            $this->filters[] = $filter;
        }
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function isValid($input)
    {
        foreach ($this->getFilters() as $filter) {
            if (! $filter->isValid($input)) {
                continue;
            }

            return true;
        }

        return false;
    }
}
