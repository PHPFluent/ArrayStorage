<?php

namespace PHPFluent\ArrayStorage\Filter;

class Not implements Filter
{
    protected $filter;

    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function isValid($input)
    {
        return ($this->getFilter()->isValid($input) === false);
    }
}
