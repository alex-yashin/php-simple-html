# php-simple-html

Класс для генерации HTML-кода. Базовые методы взяты из Yii2 (класс BaseHtml, из которого убраны все специфичные 
для Yii2 методы, имеющие сложные зависимости).

Дополнен следующими методами:

### Html::nest

```php
Html::nest($path, $content = '', $rootOptions = [])
```

Генерирует цепочку вложенных Html-тегов по шаблону, похожему на xpath.
```php
echo Html::nest('div#my-wrapper/a#my.link[href=#][title=my link]')
//<div id="my-wrapper"><a id="my" class="link" href="#" title="my link"></a></div>
```

Упоминания тега div может быть опущено, если есть ID, класс или аттрибуты. 
Параметр $content размещается в содержимом самого глубокого тега цепочки.
```php
echo Html::nest('.step/.circle+p', 10) 
//<div class="step"><div class="circle"></div><p>10</p></div>
```

Аттрибут может быть записан без значения, тогда значение будет равно имени аттрибута
```php
echo Html::nest('div/span[disabled]', 'hello!');
//<div><span disabled="disabled">hello!</span></div>
```

Параметр $rootOptions дополняет аттрибуты внешнего тега. Синтаксис такой же, как и в ```Html::tag```
```php
echo Html::nest('.step/.circle+p', 10, ['class' => 'active'])
//<div class="step active"><div class="circle"></div><p>10</p></div>
```

### Html::zz

```php
Html::zz($template, ...$items)
```

Более сложный шаблонизатор, который работает не только с одной цепочкой вложенных тегов, но и может моделировать сложное дерево тегов. Для этого отношение иерархии задаются с помощью скобок, а места для подстановок задаются символами %. Отношение соседства задаются как и в случае с nest через символ +.

```php
echo Html::zz('.circle([data-name=%]+.round%+p)+span%', 'step', 10, 20);
//<div class="circle"><div data-name="step"></div><div class="round">10</div><p></p></div><span>20</span>
```

Использовать можно и для генерации одного тега с подстановками для аттрибутов
```php
echo Html::zz('input.quantity-field[type=number][name=quantity][step=1][readonly][data-sku=%]', 'SKU');
//<input type="number" class="quantity-field" name="quantity" readonly="readonly" step="1" data-sku="SKU">
```
