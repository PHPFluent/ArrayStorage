<?php

namespace PHPFluent\ArrayStorage\Filter;

/**
 * @covers PHPFluent\ArrayStorage\Filter\Like
 */
class LikeTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldValidateWhenPatternMatch()
    {
        $pattern = 'My name is %';
        $filter = new Like($pattern);

        $this->assertTrue($filter->isValid('My name is Jhon'));
    }

    public function testShouldValidateWhenPatternDoesNotMatch()
    {
        $pattern = 'My _me is';
        $filter = new Like($pattern);

        $this->assertFalse($filter->isValid('My name is Jhon'));
    }

    public function testShouldValidateMatchingCase()
    {
        $pattern = 'Henrique Moody';
        $filter = new Like($pattern);

        $this->assertFalse($filter->isValid('henrique moody'));
    }
}
