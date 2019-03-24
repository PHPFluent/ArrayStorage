<?php

namespace PHPFluent\ArrayStorage\Filter;

/**
 * @covers PHPFluent\ArrayStorage\Filter\Regex
 */
class RegexTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldValidateWhenPatternMatch()
    {
        $pattern = '/^[0-9]{5}-[0-9]{3}$/';
        $filter = new Regex($pattern);

        $this->assertTrue($filter->isValid('12345-123'));
    }

    public function testShouldValidateWhenPatternDoesNotMatch()
    {
        $pattern = '/^[0-9]{5}-[0-9]{3}$/';
        $filter = new Regex($pattern);

        $this->assertFalse($filter->isValid('ABCDE-FGH'));
    }
}
