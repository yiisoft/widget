<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Yiisoft\Widget\Widget;

class ImmutableWidget extends Widget
{
    private string $id = 'original';

    protected function run(): string
    {
        return '<run-' . $this->id . '>';
    }

    public function id(string $value): self
    {
        $new = clone $this;
        $new->id = $value;
        return $new;
    }
}
