<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use Psr\Container\ContainerInterface;
use Yiisoft\Factory\Factory;
use Yiisoft\Factory\Exception\InvalidConfigException;
use Yiisoft\Factory\Exception\NotInstantiableException;

/**
 * WidgetFactory creates an instance of the widget based on the specified configuration
 * {@see WidgetFactory::createWidget()}. Before creating a widget, you need to initialize
 * the WidgetFactory with {@see WidgetFactory::initialize()}.
 */
final class WidgetFactory extends Factory
{
    private static ?self $factory = null;

    /**
     * @param ContainerInterface|null $container
     * @param array<string, mixed> $definitions
     *
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     *
     * @see Factory::__construct()
     */
    private function __construct(ContainerInterface $container = null, array $definitions = [])
    {
        parent::__construct($container, $definitions);
    }

    /**
     * @param ContainerInterface|null $container
     * @param array<string, mixed> $definitions
     *
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     *
     * @see Factory::__construct()
     */
    public static function initialize(ContainerInterface $container = null, array $definitions = []): void
    {
        self::$factory = new self($container, $definitions);
    }

    /**
     * Creates a widget defined by config passed.
     *
     * @param array|callable|string $config The parameters for creating a widget.
     *
     * @throws WidgetFactoryInitializationException If factory was not initialized.
     * @throws InvalidConfigException
     *
     * @see Factory::create()
     *
     * @return Widget
     *
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     */
    public static function createWidget($config): Widget
    {
        if (self::$factory === null) {
            throw new WidgetFactoryInitializationException(
                'Widget factory should be initialized with WidgetFactory::initialize() call.',
            );
        }

        return self::$factory->create($config);
    }
}
