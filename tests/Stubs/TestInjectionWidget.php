<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Yiisoft\Widget\Widget;

final class TestInjectionWidget extends Widget
{
    public function __construct(private Injectable $injectable)
    {
    }

    public function getInjectable(): Injectable
    {
        return $this->injectable;
    }

    public function render(): string
    {
        return 'test';
    }
}
