<?php

declare(strict_types=1);

namespace PHPFluent\ArrayStorage;

use InvalidArgumentException;
use PHPFluent\ArrayStorage\Filter\Between;
use PHPFluent\ArrayStorage\Filter\EqualTo;
use PHPFluent\ArrayStorage\Filter\Filter;
use PHPFluent\ArrayStorage\Filter\GreaterThan;
use PHPFluent\ArrayStorage\Filter\ILike;
use PHPFluent\ArrayStorage\Filter\In;
use PHPFluent\ArrayStorage\Filter\LessThan;
use PHPFluent\ArrayStorage\Filter\Like;
use PHPFluent\ArrayStorage\Filter\Not;
use PHPFluent\ArrayStorage\Filter\OneOf;
use PHPFluent\ArrayStorage\Filter\Regex;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers PHPFluent\ArrayStorage\Factory
 */
class FactoryTest extends TestCase
{
    public function testShouldCreateCollection(): void
    {
        $factory = new Factory();

        $this->assertInstanceOf('PHPFluent\\ArrayStorage\\Collection', $factory->collection());
    }

    public function testShouldReturnInputIfInputIsAlreadyCollection(): void
    {
        $collection = $this
            ->getMockBuilder('PHPFluent\\ArrayStorage\\Collection')
            ->disableOriginalConstructor()
            ->getMock();

        $factory = new Factory();

        $this->assertSame($collection, $factory->collection($collection));
    }

    public function testShouldCreateNewRecord(): void
    {
        $factory = new Factory();
        $record = $factory->record();

        $this->assertInstanceOf(__NAMESPACE__.'\\Record', $record);
    }

    public function testShouldCreateNewRecordFromArray(): void
    {
        $factory = new Factory();
        $data = [];
        $record = $factory->record($data);

        $this->assertInstanceOf(__NAMESPACE__.'\\Record', $record);
    }

    public function testShouldCreateNewRecordFromStdClass(): void
    {
        $factory = new Factory();
        $data = new stdClass();
        $record = $factory->record($data);

        $this->assertInstanceOf(__NAMESPACE__.'\\Record', $record);
    }

    public function testShouldReturnTheSameInstanceWhenDataIsAlreadyRecordInstance(): void
    {
        $factory = new Factory();
        $data = new Record();
        $record = $factory->record($data);

        $this->assertSame($data, $record);
    }

    public function testShouldCreateCriteria(): void
    {
        $factory = new Factory();
        $criteria = $factory->criteria();

        $this->assertInstanceOf(__NAMESPACE__.'\\Criteria', $criteria);
    }

    public function testShouldCreateCriteriaFromKeyValueArrayOfFilters(): void
    {
        $inputFilters = [
            'foo' => $this->createMock(__NAMESPACE__.'\\Filter\\Filter'),
        ];
        $factory = new Factory();
        $criteria = $factory->criteria($inputFilters);
        $actualFilters = $criteria->getFilters();

        $this->assertEquals('foo', $actualFilters[0][0]);
        $this->assertSame($inputFilters['foo'], $actualFilters[0][1]);
    }

    public function testShouldCreateCriteriaFromKeyValueArrayOfNonFilters(): void
    {
        $inputFilters = [
            'foo' => true,
        ];
        $factory = new Factory();
        $criteria = $factory->criteria($inputFilters);
        $actualFilters = $criteria->getFilters();
        $expectedFilter = new EqualTo($inputFilters['foo']);

        $this->assertEquals('foo', $actualFilters[0][0]);
        $this->assertEquals($expectedFilter, $actualFilters[0][1]);
    }

    /**
     * @return mixed[][]
     */
    public function operatorsProvider(): array
    {
        return [
            ['=', 42, new EqualTo(42)],
            ['!=', 42, new Not(new EqualTo(42))],
            ['<', 42, new LessThan(42)],
            ['<=', 42, new OneOf([new LessThan(42), new EqualTo(42)])],
            ['>', 42, new GreaterThan(42)],
            ['>=', 42, new OneOf([new GreaterThan(42), new EqualTo(42)])],
            ['BETWEEN', [1, 3], new Between(1, 3)],
            ['NOT BETWEEN', [1, 3], new Not(new Between(1, 3))],
            ['ILIKE', 'String%', new ILike('String%')],
            ['NOT ILIKE', 'String%', new Not(new ILike('String%'))],
            ['IN', [1, 2, 3], new In([1, 2, 3])],
            ['NOT IN', [1, 2, 3], new Not(new In([1, 2, 3]))],
            ['LIKE', 'String%', new Like('String%')],
            ['NOT LIKE', 'String%', new Not(new Like('String%'))],
            ['REGEX', '/[a-z]/', new Regex('/[a-z]/')],
            ['NOT REGEX', '/[a-z]/', new Not(new Regex('/[a-z]/'))],
        ];
    }

    /**
     *@dataProvider operatorsProvider
     *
     * @param mixed $value
     */
    public function testShouldCreateCriteriaFromKeyValueArrayOfNonFiltersUsingOperator(
        string $operator,
        $value,
        Filter $expectedFilter
    ): void {
        $inputFilters = ['name '.$operator => $value];
        $factory = new Factory();
        $criteria = $factory->criteria($inputFilters);
        $actualFilters = $criteria->getFilters();

        $this->assertEquals($expectedFilter, $actualFilters[0][1]);
    }

    public function testShouldReturnInputIfInputIsAlreadyFilter(): void
    {
        $filter = new EqualTo(42, 'identical');

        $factory = new Factory();

        $this->assertSame($filter, $factory->filter($filter));
    }

    public function testShouldCreateFilterByNameAndArguments(): void
    {
        $expectedFilter = new EqualTo(42, 'identical');

        $factory = new Factory();
        $actualFilter = $factory->filter('equalTo', [42, 'identical']);

        $this->assertEquals($expectedFilter, $actualFilter);
    }

    public function testShouldThrowExceptionWhenCallingMethodDoesNotRefersToValidFilter(): void
    {
        $this->expectExceptionObject(
            new InvalidArgumentException('"filter" is not a valid filter name')
        );
        $factory = new Factory();
        $factory->filter('filter');
    }

    public function testShouldUseNotFilterWhenInputHasNotAsPrefix(): void
    {
        $expectedFilter = new Not(new EqualTo(42));

        $factory = new Factory();
        $actualFilter = $factory->filter('notEqualTo', [42]);

        $this->assertEquals($expectedFilter, $actualFilter);
    }

    public function testShouldUseOneOfFilterWhenInputHasContainsTheWordOr(): void
    {
        $expectedFilter = new OneOf(
            [
                new GreaterThan(42),
                new EqualTo(42),
            ]
        );

        $factory = new Factory();
        $actualFilter = $factory->filter('greaterThanOrEqualTo', [42]);

        $this->assertEquals($expectedFilter, $actualFilter);
    }
}
