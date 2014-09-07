<?php

namespace PHPFluent\ArrayStorage\Converter;

use Traversable;

interface Converter
{
    public function convert(Traversable $traversable);
}
