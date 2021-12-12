<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Yiisoft\Widget\Widget;

final class TestWidgetAfterRender extends Widget
{
    protected function run(): string
    {
        return '<run-' . self::class . '>';
    }

    protected function afterRun(string $result): string
    {
        return $result . '<after-run>';
    }
}
