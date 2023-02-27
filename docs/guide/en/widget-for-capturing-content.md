# Widget for capturing content

Some widgets can take a block of content which should be enclosed between the invocation of `begin()` and `end()`
methods. These are wrapping widgets that mimic opening and closing HTML tags that wrap some content.
They are used a bit differently:

```php
<?= MyWidget::widget()->begin() ?>
    Content
<?= MyWidget::end() ?>
```

For your widget to do this, you need to override the parent `begin()` method. Don't forget to call `parent::begin()`:

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

    public function render(): string
    {
        return (string) ob_get_clean();
    }
}
```

The package ensures that all widgets are properly opened, closed and nested.
