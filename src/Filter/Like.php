<?php

namespace PHPFluent\ArrayStorage\Filter;

class Like extends Regex
{
    public function __construct($pattern)
    {
        $regexPattern = preg_quote($pattern, '/');
        $regexPattern = strtr($regexPattern, array('_' => '.', '%' => '.*'));

        parent::__construct('/' . $regexPattern . '/');
    }
}
