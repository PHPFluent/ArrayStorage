<?php

namespace PHPFluent\ArrayStorage\Filter;

/**
 * @covers PHPFluent\ArrayStorage\Filter\EqualTo
 */
class EqualToTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldValidateUsingNormalPrecision()
    {
        $filter = new EqualTo(42, 'normal');

        $this->assertTrue($filter->isValid('42'));
    }

    public function testShouldValidateUsingIdenticalPrecision()
    {
        $filter = new EqualTo(42, 'identical');

        $this->assertFalse($filter->isValid('42'));
    }

    public function testShouldValidateUsingTypeCastingPrecision()
    {
        $expected = new \stdClass();
        $expected->foo = 42;
        $expected->bar = false;

        $actual = array('foo' => 42, 'bar' => false);

        $filter = new EqualTo($expected, 'array');

        $this->assertTrue($filter->isValid($actual));
    }

    public function testShouldValidateUsingObjectCastingPrecision()
    {
        $expected = new \DateTime('2019-09-09');

        $actual = '2019-09-09';

        $filter = new EqualTo($expected, 'DateTime');

        $this->assertTrue($filter->isValid($actual));
    }
}
