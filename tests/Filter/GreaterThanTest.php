<?php

namespace PHPFluent\ArrayStorage\Filter;

use DateTime;

/**
 * @covers PHPFluent\ArrayStorage\Filter\GreaterThan
 */
class GreaterThanTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldValidateWhenInInterval()
    {
        $minimum = DateTime::createFromFormat('Y-m-d', '2014-01-01');
        $input = DateTime::createFromFormat('Y-m-d', '2014-01-02');

        $filter = new GreaterThan($minimum);

        $this->assertTrue($filter->isValid($input));
    }

    public function testShouldValidateWhenOutOfInterval()
    {
        $filter = new GreaterThan(7);

        $this->assertFalse($filter->isValid(5));
    }
}
