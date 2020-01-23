<?php

declare(strict_types=1);

namespace Yiisoft\Widget\Tests;

use hiqdev\composer\config\Builder;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Yiisoft\Di\Container;
use Yiisoft\Widget\WidgetFactory;

abstract class TestCase extends BaseTestCase
{
    private Container $container;
    protected EventDispatcherInterface $eventDispatcher;
    protected ListenerProviderInterface $listenerProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $config = require Builder::path('tests');

        $this->container = new Container($config);

        $this->eventDispatcher = $this->container->get(EventDispatcherInterface::class);
        $this->listenerProvider = $this->container->get(ListenerProviderInterface::class);

        WidgetFactory::initialize($this->container, []);
    }

    protected function tearDown(): void
    {
        unset($this->container);

        parent::tearDown();
    }
}
