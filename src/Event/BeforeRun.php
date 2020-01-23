<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use Yiisoft\Widget\Widget;

/**
 * BeforeRun event is raised right before executing a widget.
 */
class BeforeRun implements StoppableEventInterface
{
    private Widget $widget;
    private bool $stopPropagation = false;

    public function __construct(Widget $widget)
    {
        $this->widget = $widget;
    }

    public function stopPropagation(): void
    {
        $this->stopPropagation = true;
    }

    public function isPropagationStopped(): bool
    {
        return $this->stopPropagation;
    }

    public function getWidget(): Widget
    {
        return $this->widget;
    }
}
