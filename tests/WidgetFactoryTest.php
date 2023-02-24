<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests;

use PHPUnit\Framework\TestCase;
use stdClass;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\Tests\Stubs\Car;
use Yiisoft\Widget\WidgetFactory;

final class WidgetFactoryTest extends TestCase
{
    public function dataThemesValidation(): array
    {
        return [
            [
                'Theme name must be a string. Integer value "0" given.',
                [[]],
            ],
            [
                'Theme configuration must be an array. "stdClass" given for theme "test".',
                ['test' => new stdClass()],
            ],
            [
                'Widget name must be a string. Integer value "0" given in theme "test".',
                ['test' => [new stdClass()]],
            ],
            [
                'Widget themes supports array definitions only. "stdClass" given for "Yiisoft\Widget\Tests\Stubs\Car" definition in "test" theme.',
                ['test' => [Car::class => new stdClass()]],
            ],
            [
                'Invalid definition: incorrect constructor arguments. Expected array, got int.',
                ['test' => [Car::class => ['__construct()' => 7]]],
            ],
        ];
    }

    /**
     * @dataProvider dataThemesValidation
     */
    public function testThemesValidation(string $expectedMessage, array $themes): void
    {
        $container = new SimpleContainer();

        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage($expectedMessage);
        WidgetFactory::initialize($container, themes: $themes);
    }

    public function testDefaultTheme(): void
    {
        WidgetFactory::initialize(
            new SimpleContainer(),
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
            defaultTheme: 'colorize',
        );

        $result = Car::widget(['name' => 'Test'])->render();

        $this->assertSame('Car "Test" (red)', $result);
    }

    public function testSetDefaultTheme(): void
    {
        WidgetFactory::initialize(
            new SimpleContainer(),
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
            defaultTheme: 'colorize',
        );

        WidgetFactory::setDefaultTheme('bw');

        $result = Car::widget(['name' => 'Test'])->render();

        $this->assertSame('Car "Test" (black)', $result);
    }
}
