<?php

namespace PHPFluent\ArrayStorage\Filter;

use DateTime;

/**
 * @covers PHPFluent\ArrayStorage\Filter\Between
 */
class BetweenTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldValidateWhenInInterval()
    {
        $minimum = DateTime::createFromFormat('Y-m-d', '2014-01-01');
        $maximum = DateTime::createFromFormat('Y-m-d', '2014-03-01');
        $input = DateTime::createFromFormat('Y-m-d', '2014-02-01');

        $filter = new Between($minimum, $maximum);

        $this->assertTrue($filter->isValid($input));
    }

    public function testShouldValidateWhenOutOfInterval()
    {
        $filter = new Between(1, 10);

        $this->assertFalse($filter->isValid(11));
    }
}
