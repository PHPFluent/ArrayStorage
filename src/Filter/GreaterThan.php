<?php

namespace PHPFluent\ArrayStorage\Filter;

class GreaterThan implements Filter
{
    protected $minimum;

    public function __construct($minimum)
    {
        $this->minimum = $minimum;
    }

    public function isValid($current)
    {
        return ($this->minimum < $current);
    }
}
