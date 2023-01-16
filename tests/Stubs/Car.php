<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Yiisoft\Widget\Widget;

final class Car extends Widget
{
    public function __construct(
        private string $name,
        public ?string $color = null
    ) {
    }

    public function render(): string
    {
        $result = 'Car "' . $this->name . '"';

        if ($this->color !== null) {
            $result .= ' (' . $this->color . ')';
        }

        return $result;
    }
}
