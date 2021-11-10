<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Yiisoft\Widget\WidgetFactory;
use Yiisoft\Config\Config;

/**
 * @var Config $config
 * @var array $params
 */

return [
    static function (ContainerInterface $container) use ($config, $params) {
        WidgetFactory::initialize(
            $container,
            $config->get($params['yiisoft/widget']['widgetsGroup']),
            $params['yiisoft/widget']['validateConfig']
        );
    },
];
