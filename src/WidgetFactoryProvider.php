<?php

declare(strict_types=1);

namespace Yiisoft\Widget;

use Yiisoft\Di\Container;
use Yiisoft\Di\Contracts\ServiceProviderInterface;

class WidgetFactoryProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        WidgetFactory::initialize($container);
    }
}
