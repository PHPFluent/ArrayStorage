<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use InvalidArgumentException;

class OneOf implements Filter
{
    /**
     * @var Filter[]
     */
    protected $filters = [];

    /**
     * @param Filter[] $filters
     */
    public function __construct(array $filters)
    {
        foreach ($filters as $filter) {
            if (!$filter instanceof Filter) {
                throw new InvalidArgumentException('Filter is not valid');
            }
            $this->filters[] = $filter;
        }
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($input): bool
    {
        foreach ($this->getFilters() as $filter) {
            if (!$filter->isValid($input)) {
                continue;
            }

            return true;
        }

        return false;
    }
}
