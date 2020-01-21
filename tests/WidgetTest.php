<?php
declare(strict_types = 1);

namespace Yiisoft\Widget\Tests;

use Yiisoft\Widget\Tests\Stubs\TestWidget;
use Yiisoft\Widget\Tests\Stubs\TestWidgetA;
use Yiisoft\Widget\Tests\Stubs\TestWidgetB;
use Yiisoft\Widget\Widget;
use Yiisoft\Widget\Event\AfterRun;
use Yiisoft\Widget\Event\BeforeRun;
use Yiisoft\Widget\Exception\InvalidConfigException;

class WidgetTest extends TestCase
{
    public function testWidget(): void
    {
        $output = TestWidget::widget()->id('w0')->run();

        $this->assertSame('<run-w0>', $output);
    }

    public function testWidgetArrayConfig(): void
    {
        $output = TestWidget::widget(['id()' => ['w0']])->run();

        $this->assertSame('<run-w0>', $output);
    }

    public function testBeginEnd(): void
    {
        $widget = TestWidgetA::begin()->id('test');

        $this->assertInstanceOf(Widget::class, $widget);

        $output = TestWidgetA::end();

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

    public function testShouldTriggerBeforeRun(): void
    {
        $output = null;

        // adding some listeners
        $this->listenerProvider->attach(static function (BeforeRun $event) use (&$output) {
            $output = TestWidget::widget(['id()' => ['w0']])->run();
        });

        TestWidgetA::begin()->id('tests');
        TestWidgetA::end();

        $this->assertSame('<run-w0>', $output);
    }

    public function testShouldTriggerBeforeRunAfterRun(): void
    {
        $output = null;

        // adding some listeners
        $this->listenerProvider->attach(static function (BeforeRun $event) {
            $event->getWidget()->id('testme')->run();
        });
        $this->listenerProvider->attach(static function (AfterRun $event) use (&$output) {
            $output = $event->getResult();
        });

        TestWidgetA::begin()->id('test');
        TestWidgetA::end();

        $this->assertSame('<run-testme>', $output);
    }
}
