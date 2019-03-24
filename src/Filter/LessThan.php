<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

class LessThan implements Filter
{
    /**
     * @var mixed
     */
    protected $maximum;

    /**
     * @param mixed $maximum
     */
    public function __construct($maximum)
    {
        $this->maximum = $maximum;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($current): bool
    {
        return $this->maximum > $current;
    }
}
