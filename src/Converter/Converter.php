<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Converter;

use Traversable;

interface Converter
{
    /**
     * @return mixed
     */
    public function convert(Traversable $traversable);
}
