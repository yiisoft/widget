# Configuring the widget

You can configure the widget when creating its instance, for example, the widget class must accept some ID when
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

When you need extended configuration of widget (set properties or call methods) pass array definition via `config`
parameter:

```php
<?= MyWidget::widget(config: [
    '__construct()' => [
        'id' => 'value',
    ]
    '$name' => 'Mike',
]) ?>
```

For a description of the configuration syntax, see the
[Yii Definitions](https://github.com/yiisoft/definitions#arraydefinition) package documentation.
