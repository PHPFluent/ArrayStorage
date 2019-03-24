<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

class Not implements Filter
{
    /**
     * @var Filter
     */
    protected $filter;

    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    public function getFilter(): Filter
    {
        return $this->filter;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($input): bool
    {
        return $this->getFilter()->isValid($input) === false;
    }
}
