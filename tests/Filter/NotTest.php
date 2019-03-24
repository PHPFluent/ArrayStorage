<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use PHPUnit\Framework\TestCase;

/**
 * @covers PHPFluent\ArrayStorage\Filter\Not
 */
class NotTest extends TestCase
{
    protected function filter(): Filter
    {
        return $this->createMock(Filter::class);
    }

    public function testShouldAcceptFilterOnConstructor(): void
    {
        $deniableMock = $this->filter();
        $filter = new Not($deniableMock);

        $this->assertSame($deniableMock, $filter->getFilter());
    }

    public function testShouldReturnTrueWhenGivenFilterReturnsFalse(): void
    {
        $deniableMock = $this->filter();
        $deniableMock
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $filter = new Not($deniableMock);

        $this->assertTrue($filter->isValid('Some input'));
    }

    public function testShouldReturnFalseWhenGivenFilterReturnsTrue(): void
    {
        $deniableMock = $this->filter();
        $deniableMock
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $filter = new Not($deniableMock);

        $this->assertFalse($filter->isValid('Some input'));
    }
}
