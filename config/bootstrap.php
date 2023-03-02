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
            container: $container,
            definitions: $config->get($params['yiisoft/widget']['config']['definitionsGroup']),
            validate: $params['yiisoft/widget']['config']['validate'],
            themes: $config->get($params['yiisoft/widget']['config']['themesGroup']),
            defaultTheme: $params['yiisoft/widget']['defaultTheme'],
        );
    },
];
