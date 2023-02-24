# Themes

Theme is named extra configuration of widget that merged with default configuration. Themes defined in
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
        'colorize' => [
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

To apply theme to widget call the `widget()` method with `theme` argument:

```php
MyWidget::widget(theme: 'colorize');
```
