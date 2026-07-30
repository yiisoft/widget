<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use Throwable;
use Yiisoft\Definitions\Exception\NotInstantiableException as FactoryNotInstantiableException;
use Yiisoft\FriendlyException\FriendlyExceptionInterface;

final class NotInstantiableException extends FactoryNotInstantiableException implements FriendlyExceptionInterface
{
    public function __construct(
        private string $widgetClassName,
        private bool $widgetFactoryInitialized,
        private Throwable $previous,
    ) {
        $message = 'Failed to create a widget "' . $this->widgetClassName . '". ' . $previous->getMessage();
        if (!$this->widgetFactoryInitialized) {
            $message .= ' Perhaps you need to initialize "' . WidgetFactory::class . '" with DI container to resolve dependencies.';
        }

        parent::__construct($message, previous: $previous);
    }

    public function getName(): string
    {
        return 'Failed to create a widget "' . $this->widgetClassName . '". ' . $this->previous->getMessage();
    }

    public function getSolution(): ?string
    {
        if ($this->widgetFactoryInitialized) {
            return null;
        }

        $widgetFactoryClass = WidgetFactory::class;

        return <<<SOLUTION
            Perhaps you need to initialize "$widgetFactoryClass" with DI container to resolve dependencies.

            To initialize the widget factory call `WidgetFactory::initialize()` before using the widget.
            It is a good idea to do that for the whole application.

            Example:

            ```php
            /**
             * @var Psr\Container\ContainerInterface \$container
             */

            Yiisoft\Widget\WidgetFactory::initialize(
                container: \$container,
                definitions: [MyWidget::class => new MyWidget(/*...*/)],
                themes: [
                    'custom' => [
                        MyWidget::class => [
                            'setValue()' => [42],
                        ],
                    ],
                ],
                validate: true, // Whether definitions need to be validated.
            );
            ```

            See Yii example in the configuration file of this package `config/bootstrap.php`.
            SOLUTION;
    }
}
