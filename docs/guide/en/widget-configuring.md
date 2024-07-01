# Configuring the widget

Widgets could be configured on multiple levels.

- Configuration passed to the `widget()` method call;
- Widget factory configuration:
  - With definitions;
  - With themes;
- With a theme specified at the `widget()` method call.

More specific configuration has more priority. For example, configuration passed to the `widget()` method call has more priority 
than configuration through widget factory.

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

For a description of the configuration syntax, see the
[Yii Definitions](https://github.com/yiisoft/definitions#arraydefinition) package documentation.

## Widget factory configuration

Widget factory could be used to configure widgets. It is handy to use it to set global defaults.

### With definitions

You can use definitions:

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
    ],
);
```

For a description of the configuration syntax, see the
[Yii Definitions](https://github.com/yiisoft/definitions#arraydefinition) package documentation.

### With themes

Themes could be used in case you want to have multiple configuration sets and the ability to switch from one to another.
Theme configuration is named and merged with default configuration. Themes are defined in `WidgetFactory::initialize()`.
To apply the theme automatically, specify it as default theme.

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

## With a theme specified at the `widget()` method call.

If you want to use a specific theme for a single widget, it's possible to specify it at the `widget()` method's call 
with `$theme` argument:

```php
MyWidget::widget(theme: 'red-alert');
```
