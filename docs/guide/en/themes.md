# Themes

Theme is a named extra configuration of a widget that is merged with default configuration. Themes are defined in
`WidgetFactory::initialize()`:

```php
\Yiisoft\Widget\WidgetFactory::initialize(
    $container,
    definitions: [
        MyWidget::class => [
            '__construct()' => [
                'name' => 'Base',
            ],
        ],
    ],
    themes: [
        'red-alert' => [
            MyWidget::class => [
                '__construct()' => [
                    'color' => 'red',
                ],
            ],
        ],
        'black' => [
            MyWidget::class => [
                '__construct()' => [
                    'color' => 'black',
                ],
            ],
        ],
    ],
);
```

For a description of the configuration syntax, see the
[Yii Definitions](https://github.com/yiisoft/definitions#arraydefinition) package documentation.

To apply a theme to the widget call the `widget()` method with `theme` argument:

```php
MyWidget::widget(theme: 'colorize');
```
