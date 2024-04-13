# Widget para capturar conteúdo

Alguns widgets podem receber um bloco de conteúdo que deve ser colocado entre a invocação dos métodos `begin()` e `end()`.
Estes são widgets de empacotamento que imitam a abertura e o fechamento de tags HTML que envolvem algum conteúdo.
Eles são usados de maneira um pouco diferente:

```php
<?= MyWidget::widget()->begin() ?>
    Content
<?= MyWidget::end() ?>
```

Para que seu widget faça isso, você precisa substituir o método pai `begin()`. Não se esqueça de chamar `parent::begin()`:

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

O pacote garante que todos os widgets sejam abertos, fechados e aninhados corretamente.
