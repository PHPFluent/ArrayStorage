<?php

namespace PHPFluent\ArrayStorage\Filter;

/**
 * @covers PHPFluent\ArrayStorage\Filter\OneOf
 */
class OneOfTest extends \PHPUnit\Framework\TestCase
{
    protected function filter($return = false)
    {
        return $this->createMock('PHPFluent\ArrayStorage\Filter\Filter');
    }

    public function testShouldAcceptFiltersOnConstructor()
    {
        $filters = array(
            $this->filter(),
            $this->filter(),
        );
        $filter = new OneOf($filters);

        $this->assertSame($filters, $filter->getFilters());
    }

    public function testShouldThrowsExceptionWhenFilterIsNotValid()
    {
        $this->expectExceptionObject(
            new \InvalidArgumentException('Filter is not valid')
        );
        $filters = array(
            $this->filter(),
            'foo',
        );
        $filter = new OneOf($filters);
    }

    public function testShouldReturnFalseIfAllFiltersAreInvalid()
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

        $filters = array(
            $filter1,
            $filter2,
        );

        $filter = new OneOf($filters);

        $this->assertFalse($filter->isValid('Value'));
    }

    public function testShouldReturnTrueIfAtLeastOneFilterIsValid()
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

        $filters = array(
            $filter1,
            $filter2,
        );

        $filter = new OneOf($filters);

        $this->assertTrue($filter->isValid('Value'));
    }
}
