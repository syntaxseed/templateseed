# Setting Parameters

Parameters are how values are passed into your template. They can be set individually or as an array.

Parameters can be any valid PHP value, object, or array.

As an array to the render method:
```php
return $tpl->render('homepage', [
    'title' => 'Home',
    'pagenum' => 1
]);
```

Or, individually:
```php
$tpl->setTemplate('homepage');
$tpl->params->title = 'Home';
$tpl->params->pagenum = 1;
$tpl->output();
```

## Accessing from within a template.

Parameters are available within the template as a variable. You can interact with them via standard PHP loops, if statements, etc.

```php
<title>My Website - <?=$title;?></title>
```
