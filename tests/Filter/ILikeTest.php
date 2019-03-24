<?php

namespace PHPFluent\ArrayStorage\Filter;

/**
 * @covers PHPFluent\ArrayStorage\Filter\ILike
 */
class ILikeTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldValidateWhenPatternMatch()
    {
        $pattern = 'My name is %';
        $filter = new ILike($pattern);

        $this->assertTrue($filter->isValid('my name is jhon'));
    }

    public function testShouldValidateWhenPatternDoesNotMatch()
    {
        $pattern = 'My _me is';
        $filter = new ILike($pattern);

        $this->assertFalse($filter->isValid('My name is Jhon'));
    }

    public function testShouldValidateWithoutMatchingCase()
    {
        $pattern = 'Henrique Moody';
        $filter = new ILike($pattern);

        $this->assertTrue($filter->isValid('henrique moody'));
    }
}
