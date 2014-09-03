<?php

namespace PHPFluent\ArrayStorage\Filter;

/**
 * @covers PHPFluent\ArrayStorage\Filter\Like
 */
class LikeTest extends \PHPUnit_Framework_TestCase
{
    public function patternsProvider()
    {
        return array(
            array('String', '/String/'),
            array('%String', '/.*String/'),
            array('Str_ng', '/Str.ng/'),
            array('Str/ng', '/Str\/ng/'),
        );
    }

    /**
     * @dataProvider patternsProvider
     */
    public function testShouldTransformPatternsToRegexOnConstructor($pattern, $regex)
    {
        $filter = new Like($pattern);

        $this->assertAttributeSame($regex, 'pattern', $filter);
    }

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
