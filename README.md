<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px" alt="Yii">
    </a>
    <h1 align="center">Yii Widget</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/widget/v/stable.png)](https://packagist.org/packages/yiisoft/widget)
[![Total Downloads](https://poser.pugx.org/yiisoft/widget/downloads.png)](https://packagist.org/packages/yiisoft/widget)
[![Build status](https://github.com/yiisoft/widget/workflows/build/badge.svg)](https://github.com/yiisoft/widget/actions?query=workflow%3Abuild)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/widget/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/widget/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/widget/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/widget/?branch=master)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fwidget%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/widget/master)
[![static analysis](https://github.com/yiisoft/widget/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/widget/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/widget/coverage.svg)](https://shepherd.dev/github/yiisoft/widget)

Widgets are reusable building blocks used to create complex and configurable
user interface elements in an object-oriented fashion.

This package provides an abstract class and a factory for creating widgets,
ready-made widgets are provided in the [yiisoft/yii-widgets](https://github.com/yiisoft/yii-widgets) package.

## Requirements

- PHP 8.0 or higher.

## Installation

The package could be installed with [Composer](https://getcomposer.org):


```shell
composer require yiisoft/widget
```

## General usage

In order to implement your own widget, you need to create a class that extends the abstract class
`Yiisoft\Widget\Widget`. In most cases it is enough to implement `render()` method.

```php
final class MyWidget extends \Yiisoft\Widget\Widget
{
    public function render(): string
    {
        return 'My first widget.'.
    }
}
```

To get the string "My first widget." in the view, call the `widget()` method. Inside which the
`Yiisoft\Widget\WidgetFactory` will create an instance of the `MyWidget`, and when converting the object
to a string, the declared `render()` method will be called.

```php
<?= MyWidget::widget() ?>
```

The `Yiisoft\Widget\WidgetFactory` factory uses a [Factory](https://github.com/yiisoft/factory)
instance to create widget objects, so you can require dependencies by listing them in your widget's constructor
and set default values when initializing the factory. To initialize the widget factory call
`WidgetFactory::initialize()` once before using widgets:

```php
/**
 * @var \Psr\Container\ContainerInterface $container
 */
 
$widgetDefaults = [
    MyWidget::class => [
        'withNumber()' => [42],
    ],
];

\Yiisoft\Widget\WidgetFactory::initialize($container, $widgetDefaults);
```

It is a good idea to do that for the whole application. See Yii example in the configuration file of this package
[`config/bootstrap.php`](https://github.com/yiisoft/widget/blob/master/config/bootstrap.php).

## Documentation

- Guide: [English](docs/guide/en/README.md), [Português - Brasil](docs/guide/pt-BR/README.md)
- [Internals](docs/internals.md)

If you need help or have a question, the [Yii Forum](https://forum.yiiframework.com/c/yii-3-0/63) is a good place for that.
You may also check out other [Yii Community Resources](https://www.yiiframework.com/community).

## License

The Yii Widget is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).

## Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

## Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)
