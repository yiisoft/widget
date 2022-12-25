<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use RuntimeException;
use Stringable;
use Yiisoft\Definitions\Exception\CircularReferenceException;
use Yiisoft\Definitions\Exception\InvalidConfigException;
use Yiisoft\Definitions\Exception\NotInstantiableException;
use Yiisoft\Factory\NotFoundException;
use Yiisoft\Html\NoEncodeStringableInterface;

use function array_key_exists;
use function array_pop;
use function is_array;
use function sprintf;

/**
 * Widget generates a string content based on some logic and input data.
 * These are typically used in templates to conceal complex HTML rendering logic.
 *
 * This is the base class that is meant to be inherited when implementing your own widgets.
 */
abstract class Widget implements NoEncodeStringableInterface, Stringable
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

        return (string) $widget->render();
    }

    /**
     * Creates a widget instance.
     *
     * @param array|callable|string $config The parameters for creating a widget.
     *
     * @throws InvalidConfigException
     * @throws CircularReferenceException
     * @throws NotInstantiableException
     * @throws NotFoundException
     *
     * @return static The widget instance.
     */
    final public static function widget(array|callable|string $config = []): self
    {
        if (is_array($config) && !array_key_exists('class', $config)) {
            $config['class'] = static::class;
        }

        return WidgetFactory::createWidget($config);
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
        return (string) $this->render();
    }

    /**
     * Renders widget content.
     *
     * This method must be overridden when implementing concrete widget.
     *
     * @return string|Stringable The result of widget execution to be outputted.
     */
    abstract public function render(): string|Stringable;
}
