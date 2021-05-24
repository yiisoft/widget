<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use RuntimeException;
use Yiisoft\FriendlyException\FriendlyExceptionInterface;

final class WidgetFactoryInitializationException extends RuntimeException implements FriendlyExceptionInterface
{
    public function getName(): string
    {
        return 'WidgetFactory is not initialized.';
    }

    public function getSolution(): ?string
    {
        return <<<SOLUTION
            The `WidgetFactory::initialize()` method must be called when the application is initialized.
            Yii application templates use service providers that are implementations of `Yiisoft\Di\Contracts\ServiceProviderInterface`.
            See the example in the configuration file of this package `config/providers.php`.
        SOLUTION;
    }
}
