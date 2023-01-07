<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

final class Tag
{
    public static function create(string $widget): string
    {
        return $widget;
    }
}
