# Configuring the widget

## Configuration concept

Widget configuration is combined from multiple parts. More specific configuration has more priority. Here is the list of
configuration options arranged by priority (from the highest priority to the lowest priority):

- [configuration passed to the `widget()` method call](#configuration-passed-to-the-widget-method-call).
- [extra configuration from the widget itself by specified theme](#extra-configuration-from-the-widget-itself-by-specified-theme);
- [configuration defined in widget factory themes](#configuration-defined-in-widget-factory-themes);
- [configuration defined in widget factory definitions](#configuration-defined-in-widget-factory-definitions).

Widget factory based configuration (the latter 2 ways) is handy to use it to set global defaults), while widget based 
configuration (the former 2 ways) is suitable for non-reusable options.

Configuration is declared using [Yii Definitions](https://github.com/yiisoft/definitions) syntax. It 
allows to set properties, call methods. Example of config represented as array definition:

```php
[
    '__construct()' => [
        'id' => 'value',
    ]
    '$name' => 'Mike',
];
```

In case you want to have multiple configuration sets and the ability to switch from one to another, themes could be
used. Theme configuration is named and merged with default configuration.

## Extra configuration from the widget itself by specified theme

Themes can be defined in the custom widget itself, in the class extended from `Yiisoft\Widget\Widget`.

```php
final class MyWidget extends Yiisoft\Widget\Widget
{
    // ..

    final protected static function getThemeConfig(?string $theme): array
    {
        return match ($theme) {
            'red-alert' => [
                '__construct()' => [
                    'color' => 'red',
                ],
            ],
            'black' => [
                '__construct()' => [
                    'color' => 'black',
                ],
            ],
        };
    }

    // ...
}
```

## Configuration passed to the `widget()` method call

You can configure the widget when creating its instance. For example, the widget class must accept some ID when
initializing the object.

```php
final class MyWidget extends \Yiisoft\Widget\Widget
{
    public string $name;
    
    public function __construct(
        private string $id,
    ) {
    }

    public function render(): string
    {
        return $this->id . ' / ' . $this->name;
    }
}
```

To set a value for the ID, you can pass it to the `widget()` method:

```php
<?= MyWidget::widget([
    'id' => 'value',
]) ?>
```

When you need extended configuration of a widget (to set properties or call methods) pass array definition via `config`
parameter:

```php
<?= MyWidget::widget(
    config: [
        '__construct()' => [
            'id' => 'value',
        ]
        '$name' => 'Mike',
    ]
) ?>
```

If you want to use a specific theme for a single widget, it's possible to specify it at the `widget()` method's call as 
well with `$theme` argument:

```php
MyWidget::widget(theme: 'red-alert');
```

## Configuration defined in widget factory themes

Themes are defined in `WidgetFactory::initialize()`. To apply the theme automatically, specify it as default theme.

```php
\Yiisoft\Widget\WidgetFactory::initialize(
    /** @var \Psr\Container\ContainerInterface $container */
    $container,
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
    defaultTheme: 'black',
);
```

## Configuration defined in widget factory definitions

Usage is similar to individual widget configuration but you need to create a mapping instead:  

```php
\Yiisoft\Widget\WidgetFactory::initialize(
    /** @var \Psr\Container\ContainerInterface $container */
    $container,
    definitions: [
        MyWidget::class => [
            '__construct()' => [
                'name' => 'Base',
            ],
        ],
        MySecondWidget::class => [
            '__construct()' => [
                'id' => 'value',
            ],
        ],
    ],
);
```
