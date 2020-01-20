<?php
declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Yiisoft\EventDispatcher\Dispatcher;
use Yiisoft\EventDispatcher\Provider\Provider;
use Yiisoft\Widget\Widget;
use Yiisoft\Widget\Factory\WidgetFactory;

return [
    ListenerProviderInterface::class => [
        '__class' => Provider::class,
    ],

    EventDispatcherInterface::class => function (ContainerInterface  $container) {
        return new Dispatcher($container->get(ListenerProviderInterface::class));
    },

    Widget::class => new WidgetFactory(),
];
