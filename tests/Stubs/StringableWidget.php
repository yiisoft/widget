<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Stringable;
use Yiisoft\Widget\Widget;

final class StringableWidget extends Widget
{
    protected function run(): Stringable
    {
        return new StringableObject('run');
    }
}
