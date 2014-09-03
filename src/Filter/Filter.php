<?php

namespace PHPFluent\ArrayStorage\Filter;

interface Filter
{
    public function isValid($value);
}
