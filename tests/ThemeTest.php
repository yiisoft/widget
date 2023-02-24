<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\Tests\Stubs\Car;
use Yiisoft\Widget\WidgetFactory;

final class ThemeTest extends TestCase
{
    public function testBase(): void
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

        $result = Car::widget(
            ['name' => 'Speed'],
            theme: 'colorize'
        )->render();

        $this->assertSame('Car "Speed" (red)', $result);
    }

    public function dataConfigCombinations(): array
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
     * @dataProvider dataConfigCombinations
     */
    public function testConfigCombinations(
        string $expected,
        array $constructorArguments,
        array $config,
        ?string $theme
    ): void {
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

    public function testNonExistTheme(): void
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
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Theme "test" not found.');
        Car::widget(theme: 'test');
    }
}
