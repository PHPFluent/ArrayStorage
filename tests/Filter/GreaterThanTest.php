<?php

namespace PHPFluent\ArrayStorage\Filter;

use DateTime;

/**
 * @covers PHPFluent\ArrayStorage\Filter\GreaterThan
 */
class GreaterThanTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldAcceptMinimumOnConstructor()
    {
        $minimum = 1;

        $filter = new GreaterThan($minimum);

        $this->assertAttributeSame($minimum, 'minimum', $filter);
    }

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
