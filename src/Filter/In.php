<?php

namespace PHPFluent\ArrayStorage\Filter;

class In implements Filter
{
    public function __construct(array $haystack)
    {
        $this->haystack = $haystack;
    }

    public function isValid($needle)
    {
        return in_array($needle, $this->haystack);
    }
}
