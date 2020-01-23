<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Event;

use Yiisoft\Widget\Widget;

/**
 * AfterRun event is raised right after executing a widget.
 */
class AfterRun
{
    private Widget $widget;
    private string $result;

    public function __construct(Widget $widget, string $result)
    {
        $this->result = $result;
        $this->widget = $widget;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function getWidget(): Widget
    {
        return $this->widget;
    }
}
