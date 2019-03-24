<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

class Between implements Filter
{
    /**
     * @var mixed
     */
    private $minimum;

    /**
     * @var mixed
     */
    private $maximum;

    /**
     * @param mixed $minimum
     * @param mixed $maximum
     */
    public function __construct($minimum, $maximum)
    {
        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($between): bool
    {
        return $between >= $this->minimum && $between <= $this->maximum;
    }
}
