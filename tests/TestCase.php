<?php
declare(strict_types = 1);

namespace Yiisoft\Widget\Tests;

use hiqdev\composer\config\Builder;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Di\Container;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var EventDispatcherInterface $eventDispatcher
     */
    protected $eventDispatcher;

    /**
     * setUp
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $config = require Builder::path('tests');

        $this->container = new Container($config);

        $this->eventDispatcher = $this->container->get(EventDispatcherInterface::class);
    }

    /**
     * tearDown
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->container = null;
        parent::tearDown();
    }
}
