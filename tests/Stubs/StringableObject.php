<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Stringable;

final class StringableObject implements Stringable
{
    public function __construct(private $string = '')
    {
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
