# Configurando o widget

Você pode configurar o widget ao criar sua instância. Por exemplo, a classe do widget deve aceitar algum ID quando
inicializar o objeto.

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

Para definir um valor para o ID, você pode passá-lo para o método `widget()`:

```php
<?= MyWidget::widget([
    'id' => 'value',
]) ?>
```

Quando você precisar de configuração estendida de um widget (para definir propriedades ou chamar métodos), passe a definição do array via
parâmetro `config`:

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

Para obter uma descrição da sintaxe de configuração, consulte a documentação do pacote
[Yii Definitions](https://github.com/yiisoft/definitions#arraydefinition).
