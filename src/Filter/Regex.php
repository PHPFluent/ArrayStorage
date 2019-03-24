<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use function preg_match;

class Regex implements Filter
{
    /**
     * @var string
     */
    protected $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($subject): bool
    {
        return preg_match($this->pattern, $subject) > 0;
    }
}
