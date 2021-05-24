<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use RuntimeException;
use Yiisoft\FriendlyException\FriendlyExceptionInterface;

final class WidgetFactoryInitializationException extends RuntimeException implements FriendlyExceptionInterface
{
    public function getName(): string
    {
        return 'Failed to create a widget because WidgetFactory is not initialized.';
    }

    public function getSolution(): ?string
    {
        return <<<SOLUTION
            To initialize the widget factory call `WidgetFactory::initialize()` before using the widget.
            It is a good idea to do that for the whole application.
            See Yii example in the configuration file of this package `config/providers.php`.
        SOLUTION;
    }
}
