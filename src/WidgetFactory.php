<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Factory\Factory;
use Yiisoft\Factory\FactoryInterface;

final class WidgetFactory extends Factory
{
    private static ?FactoryInterface $factory = null;

    private function __construct(ContainerInterface $container = null, array $definitions = [])
    {
        parent::__construct($container, $definitions);
    }

    public static function initialize(ContainerInterface $container = null, array $definitions = []): void
    {
        self::$factory = new self($container, $definitions);
    }

    /**
     * Creates a widget defined by config passed
     *
     * @param string|array|callable $config parameters for creating a widget
     * @throws \RuntimeException if factory was not initialized
     * @throws \Yiisoft\Factory\Exceptions\InvalidConfigException
     */
    public static function createWidget($config): Widget
    {
        if (self::$factory === null) {
            self::initialize();
        }

        $widget = static::$factory->create($config);
        assert($widget instanceof Widget);

        /** @var ContainerInterface|null $container */
        $container = self::$factory->container;

        if ($container && $eventDispatcher = $container->get(EventDispatcherInterface::class)) {
            $widget->setEventDispatcher($eventDispatcher);
        }

        return $widget;
    }
}
