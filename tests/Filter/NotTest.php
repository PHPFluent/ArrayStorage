<?php

namespace PHPFluent\ArrayStorage\Filter;

/**
 * @covers PHPFluent\ArrayStorage\Filter\Not
 */
class NotTest extends \PHPUnit\Framework\TestCase
{
    protected function filter()
    {
        return $this->createMock('PHPFluent\ArrayStorage\Filter\Filter');
    }

    public function testShouldAcceptFilterOnConstructor()
    {
        $deniableMock = $this->filter();
        $filter = new Not($deniableMock);

        $this->assertSame($deniableMock, $filter->getFilter());
    }

    public function testShouldReturnTrueWhenGivenFilterReturnsFalse()
    {
        $deniableMock = $this->filter();
        $deniableMock
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $filter = new Not($deniableMock);

        $this->assertTrue($filter->isValid('Some input'));
    }

    public function testShouldReturnFalseWhenGivenFilterReturnsTrue()
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
