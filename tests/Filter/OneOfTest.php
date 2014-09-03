<?php

namespace PHPFluent\ArrayStorage\Filter;

/**
 * @covers PHPFluent\ArrayStorage\Filter\OneOf
 */
class OneOfTest extends \PHPUnit_Framework_TestCase
{
    protected function filter($return = false)
    {
        return $this->getMock('PHPFluent\ArrayStorage\Filter\Filter');
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

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Filter is not valid
     */
    public function testShouldThrowsExceptionWhenFilterIsNotValid()
    {
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
