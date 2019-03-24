<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @covers PHPFluent\ArrayStorage\Filter\GreaterThan
 */
class GreaterThanTest extends TestCase
{
    public function testShouldValidateWhenInInterval(): void
    {
        $minimum = DateTime::createFromFormat('Y-m-d', '2014-01-01');
        $input = DateTime::createFromFormat('Y-m-d', '2014-01-02');

        $filter = new GreaterThan($minimum);

        $this->assertTrue($filter->isValid($input));
    }

    public function testShouldValidateWhenOutOfInterval(): void
    {
        $filter = new GreaterThan(7);

        $this->assertFalse($filter->isValid(5));
    }
}
