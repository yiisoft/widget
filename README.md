<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://github.com/yiisoft.png" height="100px">
    </a>
    <h1 align="center">Yii Widget</h1>
    <br>
</p>

Widgets are reusable building blocks used to create complex and configurable user interface elements in an object-oriented fashion.

[![Latest Stable Version](https://poser.pugx.org/yiisoft/widget/v/stable.png)](https://packagist.org/packages/yiisoft/widget)
[![Total Downloads](https://poser.pugx.org/yiisoft/widget/downloads.png)](https://packagist.org/packages/yiisoft/widget)
[![Build status](https://github.com/yiisoft/widget/workflows/build/badge.svg)](https://github.com/yiisoft/widget/actions?query=workflow%3Abuild)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/widget/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/widget/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/widget/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/widget/?branch=master)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fwidget%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/widget/master)
[![static analysis](https://github.com/yiisoft/widget/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/widget/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/widget/coverage.svg)](https://shepherd.dev/github/yiisoft/widget)

## Instalation

The package could be installed via composer:

`composer require yiisoft/widget`

## General usage

Widget without begin/end:

```php
$widget = MyWidget::widget()->options(['class' => 'testMe']);
$widget->render();
```

Widget with begin/end:

```php
$widget = MyWidget::widget()->options(['class' => 'testMe']);
$widget->begin();
    // content
$widget::end();
```

```php
MyWidget::widget()->options(['class' => 'testMe'])->begin();
    // content
MyWidget::end();
```

You must define the `begin()` method of your widget like this:

```php
public function begin()
{
    parent::begin();

    // your content
}
```

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework. To run it:

```shell
./vendor/bin/infection
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

### Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

### Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)

## License

The Yii Widget is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).
