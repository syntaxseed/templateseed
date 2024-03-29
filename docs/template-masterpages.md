# Template Masterpages

Often we want to include templates into other (outer) templates. These outer templates are called masterpages.

Define a master template:

masterpage.tpl.php:
```php
<html>
<head><title><?=$title;?></title></head>
<body>
    <?php
    $_view($page, $data);
    ?>
</body>
</html>
```

Define your inner template:

pages/about.tpl.php
```php
<p>This is all about <?=$company;?>.</p>
```

Calling from inside your controller:
```php
return $tpl->render(
        'masterpage',
        [
            'page'=>'pages/about',
            'title'=>'About Our Company',
            'data'=>['company'=>'Acme Co.']
        ], true // Prevent cache of masterpage.
    );
```

> **TIP:** When using caching with masterpages (or any kind of template inside a template), you must prevent the master page from being cached by passing true for `$preventCache` after the params array. Alternatively you can set a manual cache key name to prevent all pages from being cashed as if they are the masterpage.
