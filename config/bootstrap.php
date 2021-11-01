<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Yiisoft\Widget\WidgetFactory;
use Yiisoft\Config\Config;

/** @var $config Config */

return [
    function (ContainerInterface $container) use ($config) {
        WidgetFactory::initialize($container, $config->get('widgets'));
    },
];
