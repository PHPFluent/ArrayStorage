<?php

namespace PHPFluent\ArrayStorage\Filter;

class Between implements Filter
{
    protected $minimum;
    protected $maximum;

    public function __construct($minimum, $maximum)
    {
        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }

    public function isValid($between)
    {
        return ($between >= $this->minimum && $between <= $this->maximum);
    }
}
