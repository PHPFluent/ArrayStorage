<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

class GreaterThan implements Filter
{
    /**
     * @var mixed
     */
    protected $minimum;

    /**
     * @param mixed $minimum
     */
    public function __construct($minimum)
    {
        $this->minimum = $minimum;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($current): bool
    {
        return $this->minimum < $current;
    }
}
