<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use PHPUnit\Framework\TestCase;

/**
 * @covers PHPFluent\ArrayStorage\Filter\ILike
 */
class ILikeTest extends TestCase
{
    public function testShouldValidateWhenPatternMatch(): void
    {
        $pattern = 'My name is %';
        $filter = new ILike($pattern);

        $this->assertTrue($filter->isValid('my name is jhon'));
    }

    public function testShouldValidateWhenPatternDoesNotMatch(): void
    {
        $pattern = 'My _me is';
        $filter = new ILike($pattern);

        $this->assertFalse($filter->isValid('My name is Jhon'));
    }

    public function testShouldValidateWithoutMatchingCase(): void
    {
        $pattern = 'Henrique Moody';
        $filter = new ILike($pattern);

        $this->assertTrue($filter->isValid('henrique moody'));
    }
}
