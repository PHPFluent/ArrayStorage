<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @covers PHPFluent\ArrayStorage\Filter\LessThan
 */
class LessThanTest extends TestCase
{
    public function testShouldValidateWhenInInterval(): void
    {
        $maximum = DateTime::createFromFormat('Y-m-d', '2014-03-01');
        $input = DateTime::createFromFormat('Y-m-d', '2014-02-01');

        $filter = new LessThan($maximum);

        $this->assertTrue($filter->isValid($input));
    }

    public function testShouldValidateWhenOutOfInterval(): void
    {
        $filter = new LessThan(10);

        $this->assertFalse($filter->isValid(11));
    }
}
