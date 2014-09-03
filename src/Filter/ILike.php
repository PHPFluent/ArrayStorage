<?php

namespace PHPFluent\ArrayStorage\Filter;

class ILike extends Like
{
    public function __construct($pattern)
    {
        parent::__construct($pattern);

        $this->pattern .= 'i';
    }
}
