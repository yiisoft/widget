<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests;

use PHPUnit\Framework\TestCase;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\Tests\Stubs\Car;
use Yiisoft\Widget\WidgetFactory;

final class ThemeTest extends TestCase
{
    public function dataBase(): array
    {
        return [
            [
                'Car "Base"',
                [],
                [],
                null,
            ],
            [
                'Car "Test"',
                ['name' => 'Test'],
                [],
                null,
            ],
            [
                'Car "Test"',
                ['name' => 'Test'],
                ['__construct()' => ['name' => 'MyTest']],
                null,
            ],
            [
                'Car "Test"',
                ['name' => 'Test'],
                ['__construct()' => ['name' => 'MyTest']],
                null,
            ],
            [
                'Car "Base" (red)',
                [],
                [],
                'colorize',
            ],
            [
                'Car "Test" (green)',
                ['name' => 'Test', 'color' => 'green'],
                [],
                'colorize',
            ],
            [
                'Car "Test" (white)',
                ['name' => 'Test', 'color' => 'white'],
                ['__construct()' => ['color' => 'green']],
                'bw',
            ],
        ];
    }

    /**
     * @dataProvider dataBase
     */
    public function testBase(string $expected, array $constructorArguments, array $config, ?string $theme): void
    {
        WidgetFactory::initialize(
            container: new SimpleContainer(),
            definitions: [
                Car::class => [
                    '__construct()' => [
                        'name' => 'Base',
                    ],
                ],
            ],
            themes: [
                'colorize' => [
                    Car::class => [
                        '__construct()' => [
                            'color' => 'red',
                        ],
                    ],
                ],
                'bw' => [
                    Car::class => [
                        '__construct()' => [
                            'color' => 'black',
                        ],
                    ],
                ],
            ],
        );

        $result = Car::widget($constructorArguments, $config, $theme)->render();

        $this->assertSame($expected, $result);
    }
}
