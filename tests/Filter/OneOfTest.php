<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage\Filter;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers PHPFluent\ArrayStorage\Filter\OneOf
 */
class OneOfTest extends TestCase
{
    protected function filter(bool $return = false): Filter
    {
        return $this->createMock(Filter::class);
    }

    public function testShouldAcceptFiltersOnConstructor(): void
    {
        $filters = [
            $this->filter(),
            $this->filter(),
        ];
        $filter = new OneOf($filters);

        $this->assertSame($filters, $filter->getFilters());
    }

    public function testShouldThrowsExceptionWhenFilterIsNotValid(): void
    {
        $this->expectExceptionObject(
            new InvalidArgumentException('Filter is not valid')
        );
        $filters = [
            $this->filter(),
            'foo',
        ];

        new OneOf($filters);
    }

    public function testShouldReturnFalseIfAllFiltersAreInvalid(): void
    {
        $filter1 = $this->filter();
        $filter1
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $filter2 = $this->filter();
        $filter2
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $filters = [
            $filter1,
            $filter2,
        ];

        $filter = new OneOf($filters);

        $this->assertFalse($filter->isValid('Value'));
    }

    public function testShouldReturnTrueIfAtLeastOneFilterIsValid(): void
    {
        $filter1 = $this->filter();
        $filter1
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $filter2 = $this->filter();
        $filter2
            ->expects($this->never())
            ->method('isValid');

        $filters = [
            $filter1,
            $filter2,
        ];

        $filter = new OneOf($filters);

        $this->assertTrue($filter->isValid('Value'));
    }
}
