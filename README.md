<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
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

The package could be installed via composer:

```shell
composer require yiisoft/widget
```

## General usage

In order to implement your own widget, you need to create a class that extends the abstract class
`Yiisoft\Widget\Widget`. In most cases it is enough to implement `run()` method.

```php
final class MyWidget extends \Yiisoft\Widget\Widget
{
    protected function run(): string
    {
        return 'My first widget.'.
    }
}
```

To get the string "My first widget." in the view, call the `widget()` method. Inside which the
`Yiisoft\Widget\WidgetFactory` will create an instance of the `MyWidget`, and when converting the object
to a string, the declared `run()` method will be called.

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

### Configuring the widget

You can configure the widget when creating its instance, for example,
the widget class must accept some ID when initializing the object.

```php
final class MyWidget extends \Yiisoft\Widget\Widget
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    protected function run(): string
    {
        return $this->id;
    }
}
```

To set a value for the ID, you can pass it in the configuration array to the `widget()` method:

```php
<?= MyWidget::widget([
    '__construct()' => [
        'id' => 'value',
    ],
]) ?>
```
Also, you can use shortcut `construct()` with constructor arguments only:

```php
<?= MyWidget::construct([
    'id' => 'value'
]) ?>
```

For a description of the configuration syntax, see the
[Yii Definitions](https://github.com/yiisoft/definitions#arraydefinition) package documentation.

### Widget for capturing content

Some widgets can take a block of content which should be enclosed between the invocation of `begin()` and `end()`
methods. These are wrapping widgets that mimic opening and closing HTML tags that wrap some content.
They are used a bit differently:

```php
<?= MyWidget::widget()->begin() ?>
    Content
<?= MyWidget::end() ?>
```

For your widget to do this, you need override the parent `begin()` method and don't forget to call `parent::begin()`:

```php
final class MyWidget extends \Yiisoft\Widget\Widget
{
    public function begin(): ?string
    {
        parent::begin();
        ob_start();
        ob_implicit_flush(false);
        return null;
    }

    protected function run(): string
    {
        return (string) ob_get_clean();
    }
}
```

The package ensures that all widgets are properly opened, closed and nested.

### Additional methods for customizing the run

In addition to the `run()` method, you can override two other methods, `beforeRun()` and `afterRun()`.

The `beforeRun()` method is called right before running the widget. The return value of the method
will determine whether the widget should continue to run. When overriding this method, make sure you
call the parent implementation like the following:

```php
protected function beforeRun(): bool
{
    if (!parent::beforeRun()) {
       return false;
    }

    // your custom code here

    return true; // or false to not run the widget
}
```

The `afterRun()` method is called right after running the widget. The return value of the method will be used
as the widget return value. If you override this method, your code should look like the following:

```php
protected function afterRun(string $result): string
{
    $result = parent::afterRun($result);
    // your custom code here
    return $result;
}
```

## Testing

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit --testdox --no-interaction
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework with
[Infection Static Analysis Plugin](https://github.com/Roave/infection-static-analysis-plugin). To run it:

```shell
./vendor/bin/roave-infection-static-analysis-plugin
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

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
