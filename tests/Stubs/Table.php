<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Yiisoft\Widget\Widget;

final class Table extends Widget
{
    public string $color = 'no color';

    public function render(): string
    {
        return 'Table (' . $this->color . ')';
    }

    protected static function getThemeConfig(?string $theme): array
    {
        return [
            '$color' => match ($theme) {
                'colorize' => 'Red',
                'bw' => 'white',
                default => 'transparent',
            },
        ];
    }
}
