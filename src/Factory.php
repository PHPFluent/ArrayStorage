<?php

namespace PHPFluent\ArrayStorage;

class Factory
{
    public function collection($collection = null)
    {
        if (! $collection instanceof Collection) {
            $collection = new Collection($this);
        }

        return $collection;
    }
}
