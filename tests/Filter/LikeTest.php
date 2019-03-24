<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use PHPUnit\Framework\TestCase;

/**
 * @covers PHPFluent\ArrayStorage\Filter\Like
 */
class LikeTest extends TestCase
{
    public function testShouldValidateWhenPatternMatch(): void
    {
        $pattern = 'My name is %';
        $filter = new Like($pattern);

        $this->assertTrue($filter->isValid('My name is Jhon'));
    }

    public function testShouldValidateWhenPatternDoesNotMatch(): void
    {
        $pattern = 'My _me is';
        $filter = new Like($pattern);

        $this->assertFalse($filter->isValid('My name is Jhon'));
    }

    public function testShouldValidateMatchingCase(): void
    {
        $pattern = 'Henrique Moody';
        $filter = new Like($pattern);

        $this->assertFalse($filter->isValid('henrique moody'));
    }
}
