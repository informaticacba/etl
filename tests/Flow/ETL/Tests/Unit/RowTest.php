<?php

declare(strict_types=1);

namespace Flow\ETL\Tests\Unit;

use Flow\ETL\Row;
use Flow\ETL\Row\Entries;
use Flow\ETL\Row\Entry\ArrayEntry;
use Flow\ETL\Row\Entry\BooleanEntry;
use Flow\ETL\Row\Entry\DateTimeEntry;
use Flow\ETL\Row\Entry\IntegerEntry;
use Flow\ETL\Row\Entry\NullEntry;
use Flow\ETL\Row\Entry\StringEntry;
use Flow\ETL\Row\Entry\StructureEntry;
use PHPUnit\Framework\TestCase;

final class RowTest extends TestCase
{
    public function is_equal_data_provider() : \Generator
    {
        yield 'equal simple same integer entries' => [
            true,
            new Row(new Entries(new IntegerEntry('1', 1), new IntegerEntry('2', 2), new IntegerEntry('3', 3))),
            new Row(new Entries(new IntegerEntry('1', 1), new IntegerEntry('2', 2), new IntegerEntry('3', 3))),
        ];
        yield 'same integer entries with different number of entries' => [
            false,
            new Row(new Entries(new IntegerEntry('1', 1), new IntegerEntry('2', 2), new IntegerEntry('3', 3))),
            new Row(new Entries(new IntegerEntry('1', 1), new IntegerEntry('2', 2))),
        ];
        yield 'simple same integer entries with different number of entries reversed' => [
            false,
            new Row(new Entries(new IntegerEntry('1', 1), new IntegerEntry('2', 2))),
            new Row(new Entries(new IntegerEntry('1', 1), new IntegerEntry('2', 2), new IntegerEntry('3', 3))),
        ];
        yield 'simple same array entries' => [
            true,
            new Row(new Entries(new ArrayEntry('json', ['foo' => ['bar' => 'baz']]))),
            new Row(new Entries(new ArrayEntry('json', ['foo' => ['bar' => 'baz']]))),
        ];
        yield 'simple same collection entries' => [
            true,
            new Row(new Entries(new StructureEntry('json', new IntegerEntry('1', 1), new IntegerEntry('2', 2), new IntegerEntry('3', 3)))),
            new Row(new Entries(new StructureEntry('json', new IntegerEntry('1', 1), new IntegerEntry('2', 2), new IntegerEntry('3', 3)))),
        ];
        yield 'simple different collection entries' => [
            false,
            new Row(new Entries(new StructureEntry('json', new IntegerEntry('5', 5), new IntegerEntry('2', 2), new IntegerEntry('3', 3)))),
            new Row(new Entries(new StructureEntry('json', new IntegerEntry('1', 1), new IntegerEntry('2', 2), new IntegerEntry('3', 3)))),
        ];
    }

    /**
     * @dataProvider is_equal_data_provider
     */
    public function test_is_equal(bool $equals, Row $row, Row $nextRow) : void
    {
        $this->assertSame($equals, $row->isEqual($nextRow));
    }

    public function test_renames_entry() : void
    {
        $row = Row::create(
            new StringEntry('name', 'just a string'),
            new BooleanEntry('active', true)
        );
        $newRow = $row->rename('name', 'new-name');

        $this->assertEquals(
            Row::create(
                new BooleanEntry('active', true),
                new StringEntry('new-name', 'just a string')
            ),
            $newRow
        );
    }

    public function test_transforms_row_to_array() : void
    {
        $row = Row::create(
            new IntegerEntry('id', 1234),
            new BooleanEntry('deleted', false),
            new DateTimeEntry('created-at', $createdAt = new \DateTimeImmutable('2020-07-13 15:00')),
            new NullEntry('phase'),
            new StructureEntry(
                'items',
                new IntegerEntry('item-id', 1),
                new StringEntry('name', 'one'),
                new IntegerEntry('item-id', 2),
                new StringEntry('name', 'two'),
                new IntegerEntry('item-id', 3),
                new StringEntry('name', 'three')
            )
        );

        $this->assertEquals(
            [
                'id' => 1234,
                'deleted' => false,
                'created-at' => $createdAt,
                'phase' => null,
                'items' => [
                    new IntegerEntry('item-id', 1),
                    new StringEntry('name', 'one'),
                    new IntegerEntry('item-id', 2),
                    new StringEntry('name', 'two'),
                    new IntegerEntry('item-id', 3),
                    new StringEntry('name', 'three'),
                ],
            ],
            $row->toArray(),
        );
    }
}
