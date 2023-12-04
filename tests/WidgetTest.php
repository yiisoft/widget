<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;
use Throwable;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Definitions\Exception\NotInstantiableException;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Widget\NotInstantiableWithoutWidgetFactoryInitializationException;
use Yiisoft\Widget\Tests\Stubs\Car;
use Yiisoft\Widget\Tests\Stubs\Garage;
use Yiisoft\Widget\Tests\Stubs\ImmutableWidget;
use Yiisoft\Widget\Tests\Stubs\Injectable;
use Yiisoft\Widget\Tests\Stubs\TestInjectionWidget;
use Yiisoft\Widget\Tests\Stubs\TestWidget;
use Yiisoft\Widget\Tests\Stubs\TestWidgetA;
use Yiisoft\Widget\Tests\Stubs\TestWidgetB;
use Yiisoft\Widget\WidgetFactory;
use Yiisoft\Widget\WidgetFactoryInitializationException;

final class WidgetTest extends TestCase
{
    private ?SimpleContainer $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->container = new SimpleContainer([
            Injectable::class => new Injectable(),
        ]);

        WidgetFactory::initialize($this->container);
    }

    protected function tearDown(): void
    {
        unset($this->container);

        parent::tearDown();
    }

    public function testWidget(): void
    {
        $output = TestWidget::widget()
            ->id('w0')
            ->render();

        $this->assertSame('<run-w0>', $output);
    }

    public function testToStringWidget(): void
    {
        $output = TestWidget::widget()->id('w0');

        $this->assertSame('<run-w0>', (string) $output);
    }

    public function testWidgetArrayConfig(): void
    {
        $output = TestWidget::widget(config: [
            'id()' => ['w0'],
        ])->render();

        $this->assertSame('<run-w0>', $output);
    }

    public function testBeginEnd(): void
    {
        TestWidgetA::widget()
            ->id('test')
            ->begin();
        $output = TestWidgetA::end();

        $this->assertSame('<run-test>', $output);
    }

    public function testWidgetWithImmutableWidget(): void
    {
        $widget = ImmutableWidget::widget()->id('new');
        $output = $widget->render();

        $this->assertSame('<run-new>', $output);
    }

    public function testBeginEndWithImmutableWidget(): void
    {
        $widget = ImmutableWidget::widget()->id('new');
        $widget->begin();
        $output = $widget::end();

        $this->assertSame('<run-new>', $output);
    }

    public function testBeginEndStaticWithImmutableWidget(): void
    {
        ImmutableWidget::widget()
            ->id('new')
            ->begin();
        $output = ImmutableWidget::end();

        $this->assertSame('<run-new>', $output);
    }

    public function testStackTrackingWithImmutableWidget(): void
    {
        $widget = ImmutableWidget::widget();
        $this->expectException(RuntimeException::class);
        $widget::end();
    }

    /**
     * @depends testBeginEnd
     */
    public function testStackTracking(): void
    {
        $widget = TestWidget::widget();
        $this->expectException(RuntimeException::class);
        $widget::end();
    }

    /**
     * @depends testBeginEnd
     */
    public function testStackTrackingDisorder(): void
    {
        $this->expectException(RuntimeException::class);
        $a = TestWidgetA::widget();
        $b = TestWidgetB::widget();
        $a::end();
        $b::end();
    }

    /**
     * @depends testBeginEnd
     */
    public function testStackTrackingDiferentClass(): void
    {
        $this->expectException(RuntimeException::class);
        TestWidgetA::widget()->begin();
        TestWidgetB::end();
    }

    public function testInjection(): void
    {
        $widget = TestInjectionWidget::widget();
        $this->assertInstanceOf(Injectable::class, $widget->getInjectable());
    }

    public function testInjectionWithoutInitialization(): void
    {
        $this->uninitializedWidgetFactory();

        $exception = null;
        try {
            TestInjectionWidget::widget();
        } catch (Throwable $exception) {
        }

        $this->assertInstanceOf(NotInstantiableWithoutWidgetFactoryInitializationException::class, $exception);
        $this->assertInstanceOf(NotInstantiableException::class, $exception->getPrevious());
        $this->assertSame(
            'Failed to create a widget "' . TestInjectionWidget::class . '". ' .
            'Can not instantiate ' .
            Injectable::class .
            '. Perhaps you need to initialize "' . WidgetFactory::class . '" with DI container to resolve dependencies.',
            $exception->getMessage()
        );
        $this->assertSame(
            'Failed to create a widget "' . TestInjectionWidget::class . '". Can not instantiate ' . Injectable::class . '.',
            $exception->getName()
        );
        $this->assertStringContainsString('`WidgetFactory::initialize()`', $exception->getSolution());
    }

    public function testNotInstantiableWithInitialization(): void
    {
        $exception = null;
        try {
            Garage::widget();
        } catch (Throwable $exception) {
        }

        $this->assertInstanceOf(NotInstantiableException::class, $exception);
        $this->assertSame('Can not instantiate ' . Car::class . '.', $exception->getMessage());
    }

    public function testWithoutInitialization(): void
    {
        $this->uninitializedWidgetFactory();

        $html = TestWidget::widget()->id('w0')->render();

        $expected = '<run-w0>';

        $this->assertSame($expected, $html);
    }

    public function testWidgetFactoryInitializationExceptionMessages(): void
    {
        $exception = new WidgetFactoryInitializationException();

        $this->assertSame('Failed to create a widget because WidgetFactory is not initialized.', $exception->getName());
        $this->assertStringContainsString('`WidgetFactory::initialize()`', $exception->getSolution());
    }

    public function testConstructorArguments(): void
    {
        $result = Car::widget(['name' => 'X'])->render();

        $this->assertSame('Car "X"', $result);
    }

    public function dataSameConstructorArgumentsAndArrayDefinition(): array
    {
        return [
            [
                'Car "X" (red)',
                ['name' => 'X'],
                ['__construct()' => ['color' => 'red']],
            ],
            [
                'Car "Y" (green)',
                ['Y'],
                ['__construct()' => ['Z', 'green']],
            ],
        ];
    }

    /**
     * @dataProvider dataSameConstructorArgumentsAndArrayDefinition
     */
    public function testSameConstructorArgumentsAndArrayDefinition(
        string $expected,
        array $constructorArguments,
        array $config,
    ): void {
        $result = Car::widget($constructorArguments, $config)->render();

        $this->assertSame($expected, $result);
    }

    public function testInvalidConstructorInConfig(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage(
            'Invalid definition: incorrect constructor arguments. Expected array, got string.'
        );
        Car::widget(['name' => 'X'], ['__construct()' => 'red']);
    }

    private function uninitializedWidgetFactory(): void
    {
        $widgetFactoryReflection = new ReflectionClass(WidgetFactory::class);
        $reflection = new ReflectionClass($widgetFactoryReflection->newInstanceWithoutConstructor());

        $property = $reflection->getProperty('factory');
        $property->setAccessible(true);
        $property->setValue($widgetFactoryReflection, null);
        $property->setAccessible(false);

        $property = $reflection->getProperty('initialized');
        $property->setAccessible(true);
        $property->setValue($widgetFactoryReflection, false);
        $property->setAccessible(false);
    }
}
