<?php
declare(strict_types = 1);

namespace Yiisoft\Widget\Tests;

use hiqdev\composer\config\Builder;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Di\Container;
use Yiisoft\Widget\Widget;

abstract class TestCase extends BaseTestCase
{
    private Container $container;

    protected EventDispatcherInterface $eventDispatcher;

    protected Widget $widget;

    protected function setUp(): void
    {
        parent::setUp();

        $config = require Builder::path('tests');

        $this->container = new Container($config);

        $this->eventDispatcher = $this->container->get(EventDispatcherInterface::class);
        $this->widget = $this->container->get(Widget::class);
    }

    protected function tearDown(): void
    {
        unset($this->container);

        parent::tearDown();
    }
}
