<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests\Stubs;

use Yiisoft\Widget\Widget;

final class TestNestedWidget extends Widget
{
    private string $id;

    public function id(string $id): self
    {
        $new = clone $this;
        $new->id = $id;

        return $new;
    }

    public function render(): string
    {
        return TestWidget::widget()->id($this->id)->render();
    }
}
