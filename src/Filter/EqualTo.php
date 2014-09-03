<?php

namespace PHPFluent\ArrayStorage\Filter;

class EqualTo implements Filter
{
    const NORMAL = 'normal';
    const IDENTICAL = 'identical';

    protected $expected;
    protected $precision;

    public function __construct($expected, $precision = self::NORMAL)
    {
        $this->expected = $expected;
        $this->precision = $precision;
    }

    protected function cast($input)
    {
        if ($input instanceof $this->precision) {
            return $input;
        }

        if (class_exists($this->precision)) {
            return new $this->precision($input);
        }

        settype($input, $this->precision);

        return $input;
    }

    public function isValid($actual)
    {
        if ($this->precision == self::NORMAL) {
            return ($this->expected == $actual);
        }

        if ($this->precision == self::IDENTICAL) {
            return ($this->expected === $actual);
        }

        return ($this->cast($this->expected) == $this->cast($actual));
    }
}
