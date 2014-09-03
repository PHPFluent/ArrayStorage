<?php

namespace PHPFluent\ArrayStorage\Filter;

class LessThan implements Filter
{
    protected $maximum;

    public function __construct($maximum)
    {
        $this->maximum = $maximum;
    }

    public function isValid($current)
    {
        return ($this->maximum > $current);
    }
}
