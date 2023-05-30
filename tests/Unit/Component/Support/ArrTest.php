<?php

declare(strict_types=1);

namespace Lombervid\ShoppingCart\Tests\Unit\Component\Support;

use PHPUnit\Framework\TestCase;
use Lombervid\ShoppingCart\Component\Support\Arr;
use PHPUnit\Framework\Attributes\DataProvider;

class ArrTest extends TestCase
{
    protected static $defaultOptions = [
        'name'     => 'shopping_cart',
        'autosave' => true,
        'tax'      => 0,
        'shipping' => [
            'amount' => 0,
            'free'   => 0,
        ],
    ];

    protected static $defaultArray = [
        'name'     => 'shopping_cart',
        'autosave' => true,
        'tax'      => 0,
        'total'    => 150.45,
        'fields'   => 'some_Fields',
        'shipping' => [
            'amount' => 50,
            'free'   => 500,
        ],
    ];

    public static function intersectKeyRecursiveProvider(): array
    {
        return [
            'empty values' => [[], []],
            'root level' => [
                ['tax' => 12],
                ['tax' => 12.0],
            ],
            'wrong value type' => [
                ['autosave' => 'true'],
                [],
            ],
            'wrong value type 2' => [
                ['tax' => ['12']],
                [],
            ],
            'sub level' => [
                ['shipping' => ['free' => 13.0]],
                ['shipping' => ['free' => 13.0]],
            ],
            'sub level wrong value type' => [
                ['shipping' => ['free' => true, 'amount' => 50]],
                ['shipping' => ['amount' => 50.0]],
            ],
            'numeric string value' => [
                ['tax' => '12', 'shipping' => ['amount' => '34.5']],
                ['tax' => 12.0, 'shipping' => ['amount' => 34.5]],
            ],
            'invalid indexes' => [
                ['some' => 23, 'name' => 'my_cart', 'shipping' => ['amount' => '34.5', 'invalid' => 23]],
                ['name' => 'my_cart', 'shipping' => ['amount' => 34.5]],
            ],
        ];
    }

    #[DataProvider('intersectKeyRecursiveProvider')]
    public function testIntersectKeyRecursive(array $options, array $expected)
    {
        $this->assertSame($expected, Arr::intersectKeyRecursive($options, self::$defaultOptions));
    }

    public function testGet()
    {
        $this->assertSame(true, Arr::get(self::$defaultArray, 'autosave'));
    }

    public function testGetReturnsDefaultWhenZeroValue()
    {
        $this->assertSame(null, Arr::get(self::$defaultArray, 'tax'));
    }

    public function testGetCheckOnlyIfKeyExists()
    {
        $this->assertSame(0, Arr::get(self::$defaultArray, 'tax', empty: false));
    }

    public function testGetDefaultValue()
    {
        $this->assertSame('default_value', Arr::get(self::$defaultArray, 'missing', default: 'default_value'));
    }

    public function testGetCheckValueType()
    {
        $this->assertSame(
            null,
            Arr::get(self::$defaultArray, 'fields', type: 'array'),
        );

        $this->assertSame(
            ['amount' => 50, 'free'   => 500],
            Arr::get(self::$defaultArray, 'shipping', type: 'array'),
        );
    }

    public function testGetCheckMultipleValueTypes()
    {
        $this->assertSame(
            0,
            Arr::get(self::$defaultArray, 'tax', type: ['double', 'integer'], empty: false),
        );
    }
}