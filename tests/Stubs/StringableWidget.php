<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Stringable;
use Yiisoft\Widget\Widget;

final class StringableWidget extends Widget
{
    public function render(): Stringable
    {
        return new StringableObject('run');
    }
}
