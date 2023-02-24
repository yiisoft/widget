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
use Yiisoft\Definitions\Helpers\DefinitionValidator;
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

    private static ?string $defaultTheme = null;

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
        bool $validate = true,
        array $themes = [],
        ?string $defaultTheme = null,
    ): void {
        self::$factory = new Factory($container, $definitions, $validate);

        if ($validate) {
            self::validateThemes($themes);
        }
        self::$themes = $themes;
        self::$defaultTheme = $defaultTheme;
    }

    public static function setDefaultTheme(?string $theme): void
    {
        self::$defaultTheme = $theme;
    }

    /**
     * Creates a widget defined by config passed.
     *
     * @param array $config The parameters for creating a widget.
     * @param string|null $theme The widget theme.
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
    public static function createWidget(array $config, ?string $theme = null): Widget
    {
        if (self::$factory === null) {
            throw new WidgetFactoryInitializationException(
                'Widget factory should be initialized with WidgetFactory::initialize() call.',
            );
        }

        $theme ??= self::$defaultTheme;

        if ($theme !== null && isset(self::$themes[$theme])) {
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

    /**
     * @throws InvalidConfigException
     */
    private static function validateThemes(array $themes): void
    {
        /** @var mixed $definitions */
        foreach ($themes as $theme => $definitions) {
            if (!is_string($theme)) {
                throw new InvalidConfigException(
                    sprintf('Theme name must be a string. Integer value "%s" given.', $theme)
                );
            }
            if (!is_array($definitions)) {
                throw new InvalidConfigException(
                    sprintf(
                        'Theme configuration must be an array. "%s" given for theme "%s".',
                        get_debug_type($definitions),
                        $theme,
                    )
                );
            }
            /** @var mixed $definition */
            foreach ($definitions as $id => $definition) {
                if (!is_string($id)) {
                    throw new InvalidConfigException(
                        sprintf('Widget name must be a string. Integer value "%s" given in theme "%s".', $id, $theme)
                    );
                }
                if (!is_array($definition)) {
                    throw new InvalidConfigException(
                        sprintf(
                            'Widget themes supports array definitions only. "%s" given for "%s" definition in "%s" theme.',
                            get_debug_type($definition),
                            $id,
                            $theme,
                        )
                    );
                }
                DefinitionValidator::validateArrayDefinition($definition, $id);
            }
        }
    }
}
