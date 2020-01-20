<?php
declare(strict_types=1);

namespace Yiisoft\Widget\Event;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * BeforeRun event is raised right before executing a widget.
 */
class BeforeRun implements StoppableEventInterface
{
    private bool $stopPropagation = false;

    public function stopPropagation(): void
    {
        $this->stopPropagation = true;
    }

    public function isPropagationStopped(): bool
    {
        return $this->stopPropagation;
    }
}
