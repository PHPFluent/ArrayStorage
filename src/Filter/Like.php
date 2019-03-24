<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use function preg_quote;
use function strtr;

class Like extends Regex
{
    public function __construct(string $pattern)
    {
        $regexPattern = preg_quote($pattern, '/');
        $regexPattern = strtr($regexPattern, ['_' => '.', '%' => '.*']);

        parent::__construct('/'.$regexPattern.'/');
    }
}
