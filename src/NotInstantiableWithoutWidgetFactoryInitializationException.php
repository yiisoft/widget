<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use Throwable;
use Yiisoft\Definitions\Exception\NotInstantiableException;
use Yiisoft\FriendlyException\FriendlyExceptionInterface;

final class NotInstantiableWithoutWidgetFactoryInitializationException
    extends NotInstantiableException
    implements FriendlyExceptionInterface
{
    public function __construct(private ?Throwable $previous)
    {
        parent::__construct(
            $previous->getMessage() .
            ' Perhaps need initialize "WidgetFactory" with container for resolve dependencies.',
            previous: $previous,
        );
    }

    public function getName(): string
    {
        return 'Failed to create a widget. ' . $this->previous->getMessage();
    }

    public function getSolution(): ?string
    {
        return <<<'SOLUTION'
            Perhaps need initialize `WidgetFactory` with container for resolve dependencies.

            To initialize the widget factory call `WidgetFactory::initialize()` before using the widget.
            It is a good idea to do that for the whole application.

            Example:

            ```php
            /**
             * @var Psr\Container\ContainerInterface $container
             */

            Yiisoft\Widget\WidgetFactory::initialize(
                container: $container,
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
