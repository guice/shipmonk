<?php

declare(strict_types=1);

namespace GP\Shipmonk\Tests;

use GP\Shipmonk\DataType;
use GP\Shipmonk\Order;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use GP\Shipmonk\SortedLinkList;

class SortedLinkListTest extends TestCase
{
    public function testValidateIterator()
    {
        $tests = [4, 7, 2, 9, 10];

        $sortedList = new SortedLinkList();
        foreach ($tests as $test) {
            $sortedList->insert($test);
        }

        $sortedArray = $tests;
        sort($sortedArray);

        $actual = [];
        foreach ($sortedList as $value) {
            $actual[] = $value;
        }

        $this->assertEquals($sortedArray, $actual);
    }

    #[DataProvider('ascendingDataProvider')]
    public function testAscendingOrders(array $values, array $expected): void
    {
        $sortedList = new SortedLinkList(Order::ASCENDING);
        foreach ($values as $test) {
            $sortedList->insert($test);
        }
        $actual = $sortedList->flatten();

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('descendingDataProvider')]
    public function testDescendingOrders(mixed $values, array $expected): void
    {
        $sortedList = new SortedLinkList(Order::DESCENDING);
        foreach ($values as $test) {
            $sortedList->insert($test);
        }
        $actual = $sortedList->flatten();

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('unsupportedDataTypesProvider')]
    public function testUnsupportedDataTypes(mixed $type, string $message): void
    {
        $this->expectExceptionMessage($message);
        $sortedList = new SortedLinkList();
        $sortedList->insert($type);
    }

    public function testIntIntoStringList() {
        $this->expectException(\InvalidArgumentException::class);
        $sortedList = new SortedLinkList();
        $sortedList->insert('test');
        $sortedList->insert(2);
    }

    public function testInvalidExplicitlyDefinedListType() {
        $this->expectException(\InvalidArgumentException::class);
        $sortedList = new SortedLinkList(null, DataType::STRING);
        $sortedList->insert(2);
    }

    public function testValidExplicitlyDefinedListType() {
        $this->expectNotToPerformAssertions();
        $sortedList = new SortedLinkList(null, DataType::STRING);
        $sortedList->insert('test');
    }

    public static function ascendingDataProvider(): array {
        return [
            [[4, 7, 2, 9, 10], [2, 4, 7, 9, 10]],
            [[ 'date', 'banana', 'egg', 'cherry', 'apple'], ['apple', 'banana', 'cherry', 'date', 'egg']],
        ];
    }

    public static function descendingDataProvider(): array {
        return [
            [[4, 7, 2, 9, 10], [10, 9, 7, 4, 2]],
            [[ 'date', 'banana', 'egg', 'cherry', 'apple'], ['egg', 'date', 'cherry', 'banana', 'apple']],
        ];
    }
    public static function unsupportedDataTypesProvider(): array {
        return [
            [false, 'Argument #1 ($value) must be of type string|int, false given'],
            [[], 'Argument #1 ($value) must be of type string|int, array given'],
            [3.2, 'Argument #1 ($value) must be of type string|int, float given'],
        ];
    }





}
