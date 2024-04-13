# Temas

Um tema é uma configuração extra nomeada de um widget que é mesclada com a configuração padrão.
Os temas são definidos em `WidgetFactory::initialize()`:

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

Para obter uma descrição da sintaxe de configuração, consulte a documentação do pacote [Yii Definitions](https://github.com/yiisoft/definitions#arraydefinition).

Para aplicar um tema ao widget, chame o método `widget()` com o argumento `theme`:

```php
MyWidget::widget(theme: 'red-alert');
```
