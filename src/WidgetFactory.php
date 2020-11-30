<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use Psr\Container\ContainerInterface;
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
     * @param array|callable|string $config parameters for creating a widget
     *
     * @throws \RuntimeException if factory was not initialized
     * @throws \Yiisoft\Factory\Exceptions\InvalidConfigException
     *
     * @psalm-suppress MoreSpecificReturnType
     *
     * @return Widget
     */
    public static function createWidget($config): Widget
    {
        if (self::$factory === null) {
            throw new \RuntimeException('Widget factory should be initialized with WidgetFactory::initialize() call.');
        }

        /** @psalm-suppress LessSpecificReturnStatement */
        return self::$factory->create($config);
    }
}
