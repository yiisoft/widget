<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Yiisoft\Widget\Widget;

final class Garage extends Widget
{
    public function __construct(Car $car)
    {
    }

    public function render(): string
    {
        return 'Car in garage.';
    }
}
