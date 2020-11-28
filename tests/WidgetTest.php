<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests;

use Yiisoft\Widget\Exception\InvalidConfigException;
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

    public function testWidgetArrayConfig(): void
    {
        $output = TestWidget::widget(['id()' => ['w0']])->render();

        $this->assertSame('<run-w0>', $output);
    }

    public function testBeginEnd(): void
    {
        TestWidgetA::begin()->id('test');
        $output = TestWidgetA::end();

        $this->assertSame('<run-test>', $output);
    }

    public function testBeginEndWithImmutableWidget(): void
    {
        ImmutableWidget::begin()->id('new');
        $output = ImmutableWidget::end();

        $this->assertSame('<run-new>', $output);
    }

    /**
     * @depends testBeginEnd
     */
    public function testStackTracking(): void
    {
        $this->expectException(InvalidConfigException::class);
        TestWidget::end();
    }

    /**
     * @depends testBeginEnd
     */
    public function testStackTrackingDisorder(): void
    {
        $this->expectException(InvalidConfigException::class);
        TestWidgetA::begin();
        TestWidgetB::begin();
        TestWidgetA::end();
        TestWidgetB::end();
    }

    public function testInjection(): void
    {
        $widget = TestInjectionWidget::widget();
        $this->assertInstanceOf(Injectable::class, $widget->getInjectable());
    }
}
