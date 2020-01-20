<?php
declare(strict_types=1);

namespace Yiisoft\Widget\Factory;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Widget\Widget;

class WidgetFactory
{
    protected static ContainerInterface $container;

    public function __invoke(ContainerInterface $container)
    {
        static::$container = $container;

        $eventDispatcher = $container->get(EventDispatcherInterface::class);

        return new Widget($eventDispatcher);
    }

    public static function createWidget(string $class)
    {
        return static::$container->get($class);
    }
}
