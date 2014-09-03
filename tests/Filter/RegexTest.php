<?php

namespace PHPFluent\ArrayStorage\Filter;

/**
 * @covers PHPFluent\ArrayStorage\Filter\Regex
 */
class RegexTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldAcceptPatternOnConstructor()
    {
        $pattern = '/[0-9]/';
        $filter = new Regex($pattern);

        $this->assertAttributeSame($pattern, 'pattern', $filter);
    }

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
