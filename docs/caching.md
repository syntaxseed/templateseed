# Caching

The default location for cached versions is a cache/ directory within the templates directory. The cache directory must be writeable. Will attempt to create it if it doesn't already exist.

Caching enabled from the start (notice the true parameter to the constructor):
```
$tpl = new TemplateSeed(__DIR__.'/src/templates/', true);
$tpl->setTemplate('header');
if( ! $tpl->cacheExists() ){
	$tpl->params->title = "My Blog"; 	// Query the DB for this value or other time consuming steps.
}
$tpl->output();
```

Enable caching later:
```
$tpl->enableCaching();
$tpl->setCacheExpiry(60);     // 1 minute TTL for cached copies.
$tpl->setCachePath();         // Defaults to <template path>/cache or pass in a path.
```

Manually set the cached copy file name (defaults to md5 of template name):
```
$tpl->setCacheKey('headercached');
```

**TIP:** Template parameters are ignored when using a cached version. If using masterpages or templates within templates, you can disable caching for the outer template by passing true to the output(), retrieve(), or render() methods.
