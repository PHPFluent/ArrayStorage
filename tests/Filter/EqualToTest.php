<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use DateTime;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers PHPFluent\ArrayStorage\Filter\EqualTo
 */
class EqualToTest extends TestCase
{
    public function testShouldValidateUsingNormalPrecision(): void
    {
        $filter = new EqualTo(42, 'normal');

        $this->assertTrue($filter->isValid('42'));
    }

    public function testShouldValidateUsingIdenticalPrecision(): void
    {
        $filter = new EqualTo(42, 'identical');

        $this->assertFalse($filter->isValid('42'));
    }

    public function testShouldValidateUsingTypeCastingPrecision(): void
    {
        $expected = new stdClass();
        $expected->foo = 42;
        $expected->bar = false;

        $actual = ['foo' => 42, 'bar' => false];

        $filter = new EqualTo($expected, 'array');

        $this->assertTrue($filter->isValid($actual));
    }

    public function testShouldValidateUsingObjectCastingPrecision(): void
    {
        $expected = new DateTime('2019-09-09');

        $actual = '2019-09-09';

        $filter = new EqualTo($expected, 'DateTime');

        $this->assertTrue($filter->isValid($actual));
    }
}
