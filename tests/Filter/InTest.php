<?php

namespace PHPFluent\ArrayStorage\Filter;

/**
 * @covers PHPFluent\ArrayStorage\Filter\In
 */
class InTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldAcceptListOnConstructor()
    {
        $haystack = array(1, 2, 3, 4, 5);
        $filter = new In($haystack);

        $this->assertAttributeSame($haystack, 'haystack', $filter);
    }

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
