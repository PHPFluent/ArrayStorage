<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @covers PHPFluent\ArrayStorage\Filter\Between
 */
class BetweenTest extends TestCase
{
    public function testShouldValidateWhenInInterval(): void
    {
        $minimum = DateTime::createFromFormat('Y-m-d', '2014-01-01');
        $maximum = DateTime::createFromFormat('Y-m-d', '2014-03-01');
        $input = DateTime::createFromFormat('Y-m-d', '2014-02-01');

        $filter = new Between($minimum, $maximum);

        $this->assertTrue($filter->isValid($input));
    }

    public function testShouldValidateWhenOutOfInterval(): void
    {
        $filter = new Between(1, 10);

        $this->assertFalse($filter->isValid(11));
    }
}
