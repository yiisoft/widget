<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Yiisoft\Widget\Widget;

final class Car extends Widget
{
    public function __construct(
        private string $name,
    ) {
    }

    protected function run(): string
    {
        return 'Car "' . $this->name . '"';
    }
}
