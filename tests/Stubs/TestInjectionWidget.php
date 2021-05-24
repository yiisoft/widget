<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Yiisoft\Widget\Widget;

final class TestInjectionWidget extends Widget
{
    private Injectable $injectable;

    public function __construct(Injectable $injectable)
    {
        $this->injectable = $injectable;
    }

    public function getInjectable(): Injectable
    {
        return $this->injectable;
    }

    protected function run(): string
    {
        return 'test';
    }
}
