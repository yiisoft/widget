<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use RuntimeException;
use Yiisoft\Definitions\ArrayDefinition;
use Yiisoft\Definitions\Exception\CircularReferenceException;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Definitions\Exception\NotInstantiableException;
use Yiisoft\Definitions\Helpers\ArrayDefinitionHelper;
use Yiisoft\Factory\NotFoundException;
use Yiisoft\Html\NoEncodeStringableInterface;

use function array_pop;
use function sprintf;

/**
 * Widget generates a string content based on some logic and input data.
 * These are typically used in templates to conceal complex HTML rendering logic.
 *
 * This is the base class that is meant to be inherited when implementing your own widgets.
 */
abstract class Widget implements NoEncodeStringableInterface
{
    /**
     * The widgets that are currently opened and not yet closed.
     * This property is maintained by {@see begin()} and {@see end()} methods.
     *
     * @var static[]
     */
    private static array $stack = [];

    /**
     * Used to open a wrapping widget (the one with begin/end).
     *
     * When implementing this method, don't forget to call `parent::begin()`.
     *
     * @return string|null Opening part of widget markup.
     */
    public function begin(): ?string
    {
        self::$stack[] = $this;
        return null;
    }

    /**
     * Checks that the widget was opened with {@see begin()}. If so, runs it and returns content generated.
     *
     * @throws RuntimeException
     */
    final public static function end(): string
    {
        if (self::$stack === []) {
            throw new RuntimeException(sprintf(
                'Unexpected "%s::end()" call. A matching "%s::begin()" is not found.',
                static::class,
                static::class,
            ));
        }

        $widget = array_pop(self::$stack);
        $widgetClass = $widget::class;

        if ($widgetClass !== static::class) {
            throw new RuntimeException(sprintf(
                'Expecting "%s::end()" call, found "%s::end()".',
                $widgetClass,
                static::class,
            ));
        }

        return $widget->render();
    }

    /**
     * Creates a widget instance.
     *
     * @param array $constructorArguments The constructor arguments.
     * @param array $config The configuration for creating a widget. For a description of the configuration syntax, see
     * array definitions documentation in the Yii Definitions by link
     * {@link https://github.com/yiisoft/definitions#arraydefinition).
     * @param string|null $theme The widget theme.
     *
     * @throws InvalidConfigException
     * @throws CircularReferenceException
     * @throws NotInstantiableException
     * @throws NotFoundException
     *
     * @return static The widget instance.
     */
    final public static function widget(
        array $constructorArguments = [],
        array $config = [],
        ?string $theme = null
    ): static {
        $config = ArrayDefinitionHelper::merge(
            static::getThemeConfig($theme),
            $config,
            empty($constructorArguments) ? [] : [ArrayDefinition::CONSTRUCTOR => $constructorArguments],
        );

        $config[ArrayDefinition::CLASS_NAME] = static::class;

        return WidgetFactory::createWidget($config, $theme);
    }

    /**
     * Returns configuration that will be merged with configuration passed to {@see widget()} method.
     *
     * @param string|null $theme The widget theme.
     *
     * @return array Configuration in the form of array definition (see syntax description in the Yii Definitions
     * documentation by link {@link https://github.com/yiisoft/definitions#arraydefinition}).
     *
     * @infection-ignore-all
     */
    protected static function getThemeConfig(?string $theme): array
    {
        return [];
    }

    /**
     * Allows not to call `->render()` explicitly:
     *
     * ```php
     * <?= MyWidget::widget(); ?>
     * ```
     */
    final public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Renders widget content.
     *
     * This method must be overridden when implementing concrete widget.
     *
     * @return string The result of widget execution to be outputted.
     */
    abstract public function render(): string;
}
