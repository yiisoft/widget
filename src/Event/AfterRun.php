<?php
declare(strict_types=1);

namespace Yiisoft\Widget\Event;

/**
 * AfterRun event is raised right after executing a widget.
 */
class AfterRun
{
    private string $result;

    public function __construct(string $result)
    {
        $this->result = $result;
    }

    public function getResult(): string
    {
        return $this->result;
    }
}
