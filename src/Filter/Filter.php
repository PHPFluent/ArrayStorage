<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

interface Filter
{
    /**
     * @param mixed $value
     */
    public function isValid($value): bool;
}
