<?php

namespace PHPFluent\ArrayStorage\Filter;

use DateTime;

/**
 * @covers PHPFluent\ArrayStorage\Filter\Between
 */
class BetweenTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldAcceptMinimumAndMaximumOnConstructor()
    {
        $minimum = 1;
        $maximum = 10;

        $filter = new Between($minimum, $maximum);

        $this->assertAttributeSame($minimum, 'minimum', $filter);
        $this->assertAttributeSame($maximum, 'maximum', $filter);
    }

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
