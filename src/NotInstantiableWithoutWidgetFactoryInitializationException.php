<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use Throwable;
use Yiisoft\Definitions\Exception\NotInstantiableException;
use Yiisoft\FriendlyException\FriendlyExceptionInterface;

final class NotInstantiableWithoutWidgetFactoryInitializationException extends NotInstantiableException implements FriendlyExceptionInterface
{
    public function __construct(
        private string $widgetClassName,
        private Throwable $previous,
    ) {
        parent::__construct(
            'Failed to create a widget "' . $this->widgetClassName . '". ' . $previous->getMessage() .
            ' Perhaps you need to initialize "' . WidgetFactory::class . '" with DI container to resolve dependencies.',
            previous: $previous,
        );
    }

    public function getName(): string
    {
        return 'Failed to create a widget "' . $this->widgetClassName . '". ' . $this->previous->getMessage();
    }

    public function getSolution(): ?string
    {
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
