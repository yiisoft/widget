<?php
declare(strict_types=1);

namespace Yiisoft\Widget;

use Psr\EventDispatcher\EventDispatcherInterface;
use Yiisoft\Widget\Exception\InvalidConfigException;
use Yiisoft\Widget\Event\AfterRun;
use Yiisoft\Widget\Event\BeforeRun;

/**
 * Widget generates a string content based on some logic and input data.
 * These are typically used in templates to conceal complex HTML rendering logic.
 *
 * This is the base class that is meant to be inherited when implementing your own widgets.
 */
abstract class Widget
{
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * The widgets that are currently being rendered (not ended). This property is maintained by {@see begin()} and
     * {@see end} methods.
     *
     * @var array $stack
     */
    private static array $stack;

    /**
     * @internal Please use {@see widget()} or {@see begin()}
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Renders widget content.
     * This method is used by {@see render()} and is meant to be overridden
     * when implementing concrete widget.
     *
     * @return string
     */
    protected function run(): string
    {
        return '';
    }

    /**
     * Creates a widget assuming it should be closed with {@see end()}
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
     * Checks that the widget was opened with {@see begin()}. If so, runs it and returns content generated.
     *
     * @return string
     * @throws InvalidConfigException
     * @throws \InvalidConfigException
     */
    final public static function end(): string
    {
        if (empty(self::$stack)) {
            throw new InvalidConfigException(
                'Unexpected ' . static::class . '::end() call. A matching begin() is not found.'
            );
        }

        /** @var static $widget */
        $widget = array_pop(self::$stack);

        if (get_class($widget) !== static::class) {
            throw new InvalidConfigException('Expecting end() of ' . get_class($widget) . ', found ' . static::class);
        }

        return $widget->render();
    }

    /**
     * Creates a widget instance.
     *
     * @return Widget $widget.
     */
    final public static function widget(): Widget
    {
        return WidgetFactory::createWidget(static::class);
    }

    /**
     * Executes the widget.
     *
     * @return string the result of widget execution to be outputted.
     */
    public function render(): string
    {
        if (!$this->beforeRun()) {
            return '';
        }

        $result = $this->run();
        return $this->afterRun($result);
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
        $event = new BeforeRun($this);

        /** @var BeforeRun $event */
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
     * @param string $result the widget return result.
     *
     * @return string the processed widget result.
     */
    public function afterRun(string $result): string
    {
        $event = new AfterRun($this, $result);

        /** @var AfterRun $event */
        $event = $this->eventDispatcher->dispatch($event);

        return $event->getResult();
    }

    /**
     * Allows not to call `->render()` explicitly:
     *
     * ```php
     * <?= MyWidget::widget()->name('test') ?>
     * ```
     *
     * @return string
     * @throws \Throwable
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
