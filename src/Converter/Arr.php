<?php

namespace PHPFluent\ArrayStorage\Converter;

use Traversable;

class Arr implements Converter
{
    protected $recursive;

    public function __construct($recursive = true)
    {
        $this->recursive = $recursive;
    }

    public function convert(Traversable $traversable)
    {
        $result = array();
        foreach ($traversable as $key => $value) {
            if ($this->recursive
                && $value instanceof Traversable) {
                $value = $this->convert($value);
            }
            $result[$key] = $value;
        }

        return $result;
    }
}
