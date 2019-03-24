<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use PHPUnit\Framework\TestCase;

/**
 * @covers PHPFluent\ArrayStorage\Filter\In
 */
class InTest extends TestCase
{
    public function testShouldValidateValueIsInTheList(): void
    {
        $haystack = [1, 2, 3, 4, 5];
        $filter = new In($haystack);

        $this->assertTrue($filter->isValid(3));
    }

    public function testShouldValidateValueIsOutOfTheList(): void
    {
        $haystack = [1, 2, 3, 4, 5];
        $filter = new In($haystack);

        $this->assertFalse($filter->isValid(6));
    }
}
