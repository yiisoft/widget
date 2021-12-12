<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use Psr\Container\ContainerInterface;
use Yiisoft\Definitions\Exception\CircularReferenceException;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Definitions\Exception\NotInstantiableException;
use Yiisoft\Factory\NotFoundException;
use Yiisoft\Factory\Factory;

/**
 * WidgetFactory creates an instance of the widget based on the specified configuration
 * {@see WidgetFactory::createWidget()}. Before creating a widget, you need to initialize
 * the WidgetFactory with {@see WidgetFactory::initialize()}.
 */
final class WidgetFactory
{
    private static ?Factory $factory = null;

    private function __construct()
    {
    }

    /**
     * @param ContainerInterface $container
     * @param array $definitions
     * @param bool $validate
     *
     * @psalm-param array<string, mixed> $definitions
     *
     * @throws InvalidConfigException
     *
     * @see Factory::__construct()
     */
    public static function initialize(
        ContainerInterface $container,
        array $definitions = [],
        bool $validate = true
    ): void {
        self::$factory = new Factory($container, $definitions, $validate);
    }

    /**
     * Creates a widget defined by config passed.
     *
     * @param array|callable|string $config The parameters for creating a widget.
     *
     * @throws WidgetFactoryInitializationException If factory was not initialized.
     * @throws CircularReferenceException
     * @throws InvalidConfigException
     * @throws NotFoundException
     * @throws NotInstantiableException
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
