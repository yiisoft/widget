<?php
declare(strict_types=1);

namespace Yiisoft\Widget;

use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Widget\Exception\InvalidConfigException;
use Yiisoft\Widget\Event\AfterRun;
use Yiisoft\Widget\Event\BeforeRun;
use Yiisoft\Widget\Factory\WidgetFactory;

/**
 * Widget is the base class for widgets.
 */
class Widget
{
    /**
     * @var EventDispatcherInterface $eventDispatcher
     */
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * The widgets that are currently being rendered (not ended). This property is maintained by {@see begin()} and
     * {@see end} methods.
     *
     * @var array $stack
     */
    protected static array $stack;

    /**
     * @var Widget $widget
     */
    protected static Widget $widget;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function run(): string
    {
        return '';
    }

    /**
     * Begin the rendering of content.
     *
     * @return Widget
     */
    final public static function begin(): Widget
    {
        $widget = WidgetFactory::createWidget(static::class);

        static::$stack[] = $widget;

        return $widget;
    }

    /**
     * Ends the rendering of content.
     *
     * @return string
     */
    final public static function end(): string
    {
        if (empty(self::$stack)) {
            throw new InvalidConfigException(
                'Unexpected ' . static::class . '::end() call. A matching begin() is not found.'
            );
        }

        $widget = array_pop(self::$stack);

        if (get_class($widget) !== static::class) {
            throw new InvalidConfigException('Expecting end() of ' . get_class($widget) . ', found ' . static::class);
        }

        if ($widget->beforeRun()) {
            $result = $widget->run();
            $result = $widget->afterRun($result);
            return $result;
        }

        return '';
    }

    /**
     * Creates a widget instance.
     *
     * @return Widget $widget.
     */
    final public static function widget(): Widget
    {
        $widget = WidgetFactory::createWidget(get_called_class());

        static::$widget = $widget;

        return $widget;
    }

    /**
     * Executes the widget.
     *
     * @return string the result of widget execution to be outputted.
     */
    public function render(): string
    {
        $out = '';

        if ($this->beforeRun()) {
            $result = $this->run();
            $out = $this->afterRun($result);
        }

        return $out;
    }

    /**
     * This method is invoked right before the widget is executed.
     *
     * The method will trigger the {@see BeforeRun()} event. The return value of the method will determine whether the
     * widget should continue to run.
     *
     * When overriding this method, make sure you call the parent implementation like the following:
     *
     * ```php
     * public function beforeRun()
     * {
     *     if (!parent::beforeRun()) {
     *         return false;
     *     }
     *
     *     // your custom code here
     *
     *     return true; // or false to not run the widget
     * }
     * ```
     *
     * @return bool whether the widget should continue to be executed.
     */
    public function beforeRun(): bool
    {
        $event = new BeforeRun();
        $event = $this->eventDispatcher->dispatch($event);

        return !$event->isPropagationStopped();
    }

    /**
     * This method is invoked right after a widget is executed.
     *
     * The method will trigger the {@see AfterRun()} event. The return value of the method will be used as the widget
     * return value.
     *
     * If you override this method, your code should look like the following:
     *
     * ```php
     * public function afterRun($result)
     * {
     *     $result = parent::afterRun($result);
     *     // your custom code here
     *     return $result;
     * }
     * ```
     *
     * @param mixed $result the widget return result.
     *
     * @return mixed the processed widget result.
     */
    public function afterRun(string $result)
    {
        $event = new AfterRun($result);
        $event = $this->eventDispatcher->dispatch($event);

        return $event->getResult();
    }

    public function __toString()
    {
        return $this->render();
    }
}
