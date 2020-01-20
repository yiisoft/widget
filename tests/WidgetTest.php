<?php
declare(strict_types = 1);

namespace Yiisoft\Widget\Tests;

use Yiisoft\Widget\Tests\TestCase;
use Yiisoft\Widget\Tests\Stubs\TestWidget;
use Yiisoft\Widget\Tests\Stubs\TestWidgetA;
use Yiisoft\Widget\Tests\Stubs\TestWidgetB;
use Yiisoft\Widget\Widget;
use Yiisoft\Widget\Exception\InvalidConfigException;

class WidgetTest extends TestCase
{
    public function testWidget(): void
    {
        $output = TestWidget::widget()->id('w0')->run();

        $this->assertSame('<run-w0>', $output);
    }

    public function testBeginEnd(): void
    {
        ob_start();
        ob_implicit_flush(0);

        $widget = TestWidgetA::begin()->id('test');

        $this->assertInstanceOf(Widget::class, $widget);

        TestWidgetA::end();
        $output = ob_get_clean();

        $this->assertSame('<run-test>', $output);
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
}
