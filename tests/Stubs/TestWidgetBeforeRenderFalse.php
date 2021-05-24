<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Yiisoft\Widget\Widget;

final class TestWidgetBeforeRenderFalse extends Widget
{
    public function run(): string
    {
        return '<run-' . self::class . '>';
    }

    protected function beforeRun(): bool
    {
        return false;
    }
}
