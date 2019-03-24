<?php

namespace PHPFluent\ArrayStorage\Filter;

/**
 * @covers PHPFluent\ArrayStorage\Filter\In
 */
class InTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldValidateValueIsInTheList()
    {
        $haystack = array(1, 2, 3, 4, 5);
        $filter = new In($haystack);

        $this->assertTrue($filter->isValid(3));
    }

    public function testShouldValidateValueIsOutOfTheList()
    {
        $haystack = array(1, 2, 3, 4, 5);
        $filter = new In($haystack);

        $this->assertFalse($filter->isValid(6));
    }
}
