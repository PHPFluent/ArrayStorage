<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

class ILike extends Like
{
    public function __construct(string $pattern)
    {
        parent::__construct($pattern);

        $this->pattern .= 'i';
    }
}
