# Caching

The default location for cached versions is a cache/ directory within the templates directory. The cache directory must be writeable. Will attempt to create it if it doesn't already exist.

Caching enabled from the start (notice the true parameter to the constructor):
```php
$tpl = new TemplateSeed(__DIR__.'/src/templates/', true);
$tpl->setTemplate('header');
if( ! $tpl->cacheExists() ){
	$tpl->params->title = "My Blog"; 	// Query the DB for this value or other time consuming steps.
}
$tpl->output();
```

Enable caching later:
```php
$tpl->enableCaching();
$tpl->setCacheExpiry(60);     // 1 minute TTL for cached copies (default is 1 hour).
$tpl->setCachePath();         // Defaults to <template path>/cache or pass in a path.
```

> **TIP:** Template parameters are ignored when using a cached version.

> **TIP:** You might want to set up a cron to clear out the cache directory periodically.

## Overriding Caching

To **prevent** caching if it was turned on in the constructor (or via `$tpl->enableCaching();`):

Pass in `true` for the `$preventCache` parameter for `retrieve`, `output` or `render`.
```php
$tpl->setTemplate('header');
$tpl->params->title = "My Blog";
$tpl->output(true);
// or
return $tpl->retrieve(true);
// or
return $tpl->render('header', ['title' => 'My Blog'], true);
```

You can override the cache setting within a template itself, because a local instance of the TemplateSeed object ($_tpl) is passed to your templates:
```php
<body>
    <?php
    $_tpl->enableCaching();
    $_view($page, $data);
    ?>
</body>
```

The `$_view()` template helper can also receive a `true` to prevent caching.

> **TIP:** Preventing caching is ***recommended for Masterpages*** or templates which contain other templates.

## Overriding Cache Filename

Manually set the cached copy file name (defaults to md5 of template name):
```php
$tpl->setCacheKey('headercached');
```
