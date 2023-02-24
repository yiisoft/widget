<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Yiisoft\Definitions\ArrayDefinition;
use Yiisoft\Definitions\Exception\CircularReferenceException;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Definitions\Exception\NotInstantiableException;
use Yiisoft\Definitions\Helpers\ArrayDefinitionHelper;
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

    /**
     * @psalm-var array<string, array<string, array>>
     */
    private static array $themes = [];

    private function __construct()
    {
    }

    /**
     * @psalm-param array<string, mixed> $definitions
     * @psalm-param array<string, array<string, array>> $themes
     *
     * @throws InvalidConfigException
     *
     * @see Factory::__construct()
     */
    public static function initialize(
        ContainerInterface $container,
        array $definitions = [],
        array $themes = [],
        bool $validate = true
    ): void {
        self::$factory = new Factory($container, $definitions, $validate);
        self::$themes = $themes;
    }

    /**
     * Creates a widget defined by config passed.
     *
     * @param array $config The parameters for creating a widget.
     *
     * @throws WidgetFactoryInitializationException If factory was not initialized.
     * @throws CircularReferenceException
     * @throws InvalidConfigException
     * @throws NotFoundException
     * @throws NotInstantiableException
     *
     * @see Factory::create()
     *
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     */
    public static function createWidget(array $config, ?string $theme): Widget
    {
        if (self::$factory === null) {
            throw new WidgetFactoryInitializationException(
                'Widget factory should be initialized with WidgetFactory::initialize() call.',
            );
        }

        if ($theme !== null) {
            if (!isset(self::$themes[$theme])) {
                throw new InvalidArgumentException(sprintf('Theme "%s" not found.', $theme));
            }

            if (
                is_string($config[ArrayDefinition::CLASS_NAME])
                && isset(
                    $config[ArrayDefinition::CLASS_NAME],
                    self::$themes[$theme][$config[ArrayDefinition::CLASS_NAME]]
                )
            ) {
                $config = ArrayDefinitionHelper::merge(
                    self::$themes[$theme][$config[ArrayDefinition::CLASS_NAME]],
                    $config
                );
            }
        }

        return self::$factory->create($config);
    }
}
