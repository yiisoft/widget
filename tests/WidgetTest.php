<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests;

use ReflectionClass;
use RuntimeException;
use Yiisoft\Widget\WidgetFactory;
use Yiisoft\Widget\Tests\Stubs\ImmutableWidget;
use Yiisoft\Widget\Tests\Stubs\Injectable;
use Yiisoft\Widget\Tests\Stubs\TestInjectionWidget;
use Yiisoft\Widget\Tests\Stubs\TestWidget;
use Yiisoft\Widget\Tests\Stubs\TestWidgetA;
use Yiisoft\Widget\Tests\Stubs\TestWidgetB;

final class WidgetTest extends TestCase
{
    public function testWidget(): void
    {
        $output = TestWidget::widget()->id('w0')->render();

        $this->assertSame('<run-w0>', $output);
    }

    public function testToStringWidget(): void
    {
        $output = TestWidget::widget()->id('w0');

        $this->assertSame('<run-w0>', (string) $output);
    }

    public function testWidgetArrayConfig(): void
    {
        $output = TestWidget::widget(['id()' => ['w0']])->render();

        $this->assertSame('<run-w0>', $output);
    }

    public function testBeginEnd(): void
    {
        TestWidgetA::widget()->id('test')->begin();
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
        ImmutableWidget::widget()->id('new')->begin();
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

    public function testFactoryExceptionWidget(): void
    {
        $reflection = new ReflectionClass(WidgetFactory::class);

        $widgetFactory = $reflection->newInstanceWithoutConstructor();
        $this->setInaccessibleProperty($widgetFactory, 'factory', null);
        $this->expectException(RuntimeException::class);
        TestWidget::widget()->id('w0')->render();
    }
}
