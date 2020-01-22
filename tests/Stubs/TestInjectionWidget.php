<?php


namespace Yiisoft\Widget\Tests\Stubs;

use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Widget\Widget;

class TestInjectionWidget extends Widget
{
    private Injectable $injectable;

    public function __construct(Injectable $injectable, EventDispatcherInterface $eventDispatcher)
    {
        $this->injectable = $injectable;
        parent::__construct($eventDispatcher);
    }

    public function getInjectable(): Injectable
    {
        return $this->injectable;
    }

    protected function run(): string
    {
        return 'test';
    }
}
