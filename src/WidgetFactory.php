<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use Psr\Container\ContainerInterface;
use Yiisoft\Definitions\ArrayDefinition;
use Yiisoft\Definitions\Exception\CircularReferenceException;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Definitions\Exception\NotInstantiableException as FactoryNotInstantiableException;
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
    private static bool $initialized = false;
    private static ?Factory $factory = null;

    /**
     * @psalm-var array<string, array<string, array>>
     */
    private static array $themes = [];

    private static ?string $defaultTheme = null;

    /**
     * @psalm-var array<string, string>
     */
    private static array $widgetDefaultThemes = [];

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @psalm-param array<string, mixed> $definitions
     * @psalm-param array<string, array<string, array>> $themes
     * @psalm-param array<string, string> $widgetDefaultThemes
     *
     * @throws InvalidConfigException
     *
     * @see Factory::__construct()
     */
    public static function initialize(
        ?ContainerInterface $container = null,
        array $definitions = [],
        bool $validate = true,
        array $themes = [],
        ?string $defaultTheme = null,
        array $widgetDefaultThemes = [],
    ): void {
        self::$factory = new Factory($container, $definitions, $validate);

        if ($validate) {
            self::assertThemesStructure($themes);
            self::assertWidgetDefaultThemesStructure($widgetDefaultThemes);
        }

        self::$themes = $themes;
        self::$defaultTheme = $defaultTheme;
        self::$widgetDefaultThemes = $widgetDefaultThemes;

        self::$initialized = true;
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
     * @throws CircularReferenceException
     * @throws InvalidConfigException
     * @throws NotFoundException
     * @throws FactoryNotInstantiableException
     *
     * @see Factory::create()
     *
     * @psalm-suppress MixedInferredReturnType
     * @psalm-suppress MixedReturnStatement
     */
    public static function createWidget(array $config, ?string $theme = null): Widget
    {
        if (self::$factory === null) {
            self::$factory = new Factory();
        }

        $className = $config[ArrayDefinition::CLASS_NAME] ?? null;
        if (is_string($className)) {
            $theme ??= self::$widgetDefaultThemes[$className] ?? self::$defaultTheme;
            if ($theme !== null && isset(self::$themes[$theme][$className])) {
                $config = ArrayDefinitionHelper::merge(
                    self::$themes[$theme][$className],
                    $config
                );
            }
        }

        try {
            return self::$factory->create($config);
        } catch (FactoryNotInstantiableException $exception) {
            /**
             * @var string $className When `$className` is not string, `$factory->create()` does not throw
             * {@see FactoryNotInstantiableException} exception.
             */
            throw new NotInstantiableException($className, self::$initialized, $exception);
        }
    }

    /**
     * @throws InvalidConfigException
     */
    private static function assertThemesStructure(array $themes): void
    {
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

    /**
     * @throws InvalidConfigException
     */
    private static function assertWidgetDefaultThemesStructure(array $value): void
    {
        foreach ($value as $widget => $theme) {
            if (!is_string($widget)) {
                throw new InvalidConfigException(
                    sprintf('Widget class must be a string. Integer value "%s" given.', $widget)
                );
            }
            if (!is_string($theme)) {
                throw new InvalidConfigException(
                    sprintf(
                        'Theme name must be a string. "%s" given for widget "%s".',
                        get_debug_type($theme),
                        $widget,
                    )
                );
            }
        }
    }
}
