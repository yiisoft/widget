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

    public static function createWidget($config): Widget
    {
        if (static::$factory === null) {
            throw new \RuntimeException('Widget factory should be initialized with WidgetFactory::initialize() call.');
        }

        return static::$factory->create($config);
    }
}
