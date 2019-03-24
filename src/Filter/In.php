<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use function in_array;

class In implements Filter
{
    /**
     * @var mixed[]
     */
    private $haystack;

    /**
     * @param mixed[] $haystack
     */
    public function __construct(array $haystack)
    {
        $this->haystack = $haystack;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($needle): bool
    {
        return in_array($needle, $this->haystack);
    }
}
