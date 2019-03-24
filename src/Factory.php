<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage;

use InvalidArgumentException;
use PHPFluent\ArrayStorage\Filter\Filter;
use PHPFluent\ArrayStorage\Filter\Not;
use PHPFluent\ArrayStorage\Filter\OneOf;
use ReflectionClass;
use function explode;
use function sprintf;
use function strpos;
use function strstr;
use function substr;
use function trim;
use function ucfirst;

class Factory
{
    /**
     * @var string[]
     */
    protected $operatorsToFilters = [
        '=' => 'equalTo',
        '!=' => 'notEqualTo',
        '<' => 'lessThan',
        '<=' => 'lessThanOrEqualTo',
        '>' => 'greaterThan',
        '>=' => 'greaterThanOrEqualTo',
        'BETWEEN' => 'between',
        'NOT BETWEEN' => 'notBetween',
        'ILIKE' => 'iLike',
        'NOT ILIKE' => 'notILike',
        'IN' => 'in',
        'NOT IN' => 'notIn',
        'LIKE' => 'like',
        'NOT LIKE' => 'notLike',
        'REGEX' => 'regex',
        'NOT REGEX' => 'notRegex',
    ];

    public function collection(?Collection $collection = null): Collection
    {
        if (!$collection instanceof Collection) {
            $collection = new Collection($this);
        }

        return $collection;
    }

    /**
     * @param Record|mixed[] $record
     */
    public function record($record = null): Record
    {
        if (!$record instanceof Record) {
            $data = (array) $record;
            $record = new Record($data);
        }

        return $record;
    }

    /**
     * @param Criteria|mixed[] $criteria
     */
    public function criteria($criteria = null): Criteria
    {
        if (!$criteria instanceof Criteria) {
            $filters = (array) $criteria;
            $criteria = new Criteria($this);
            foreach ($filters as $key => $value) {
                if ($value instanceof Filter) {
                    $criteria->addFilter($key, $value);
                    continue;
                }

                $index = strstr($key, ' ', true) ?: $key;
                $operator = trim(strstr($key, ' ') ?: '=');
                if (strpos($operator, 'BETWEEN') === false) {
                    $value = [$value];
                }
                $filter = $this->filter($operator, $value);

                $criteria->addFilter($index, $filter);
            }
        }

        return $criteria;
    }

    /**
     * @param Filter|string $filter
     * @param mixed[] $arguments
     */
    public function filter($filter, array $arguments = []): Filter
    {
        if (!$filter instanceof Filter) {
            if (isset($this->operatorsToFilters[$filter])) {
                return $this->filter($this->operatorsToFilters[$filter], $arguments);
            }

            if (strpos($filter, 'not') === 0) {
                $filterName = substr($filter, 3);
                $filter = $this->filter($filterName, $arguments);

                return new Not($filter);
            }

            if (strpos($filter, 'Or') !== false) {
                $filtersNames = explode('Or', $filter);
                $filters = [];
                foreach ($filtersNames as $filterName) {
                    $filters[] = $this->filter($filterName, $arguments);
                }

                return new OneOf($filters);
            }

            $reflectionClass = new ReflectionClass(__NAMESPACE__.'\\Filter\\'.ucfirst($filter));
            if (!$reflectionClass->isSubclassOf(__NAMESPACE__.'\\Filter\\Filter')) {
                throw new InvalidArgumentException(sprintf('"%s" is not a valid filter name', $filter));
            }

            return $reflectionClass->newInstanceArgs($arguments);
        }

        return $filter;
    }
}
