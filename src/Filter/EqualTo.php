<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use function class_exists;
use function settype;

class EqualTo implements Filter
{
    public const NORMAL = 'normal';
    public const IDENTICAL = 'identical';

    /**
     * @var mixed
     */
    protected $expected;

    /**
     * @var string
     */
    protected $precision;

    /**
     * @param mixed $expected
     */
    public function __construct($expected, string $precision = self::NORMAL)
    {
        $this->expected = $expected;
        $this->precision = $precision;
    }

    /**
     * @param mixed $input
     *
     * @return mixed
     */
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

    /**
     * {@inheritdoc}
     */
    public function isValid($actual): bool
    {
        if ($this->precision == self::NORMAL) {
            return $this->expected == $actual;
        }

        if ($this->precision == self::IDENTICAL) {
            return $this->expected === $actual;
        }

        return $this->cast($this->expected) == $this->cast($actual);
    }
}
