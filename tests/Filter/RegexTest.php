<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use PHPUnit\Framework\TestCase;

/**
 * @covers PHPFluent\ArrayStorage\Filter\Regex
 */
class RegexTest extends TestCase
{
    public function testShouldValidateWhenPatternMatch(): void
    {
        $pattern = '/^[0-9]{5}-[0-9]{3}$/';
        $filter = new Regex($pattern);

        $this->assertTrue($filter->isValid('12345-123'));
    }

    public function testShouldValidateWhenPatternDoesNotMatch(): void
    {
        $pattern = '/^[0-9]{5}-[0-9]{3}$/';
        $filter = new Regex($pattern);

        $this->assertFalse($filter->isValid('ABCDE-FGH'));
    }
}
