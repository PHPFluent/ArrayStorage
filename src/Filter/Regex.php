<?php

namespace PHPFluent\ArrayStorage\Filter;

class Regex implements Filter
{
    protected $pattern;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    public function isValid($subject)
    {
        return (preg_match($this->pattern, $subject) > 0);
    }
}
