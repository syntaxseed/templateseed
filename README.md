TemplateSeed
=========================

A simple PHP templating class with caching and natural PHP template syntax.

Licence: GPLv3.

Features
--------

* PSR-4 autoloading compliant structure.
* Unit-Testing with PHPUnit.
* Simple to learn and use.
* Template markup language is standard PHP.
* Pass any number of parameters to a template.
* Small code footprint: integrates easily into your project.
* Simple output caching.
* Easy to customize.

Usage - Basic
--------

First ensure you have a directory for your templates. Save them with the **.tpl.php** extension. They can be sorted in to subdirectories.

Require with Composer:
```
./composer.phar require syntaxseed/templateseed ~1.0
```

Import the namespace into your application:
```
use syntaxseed\templateseed\TemplateSeed;
```

Basic usage:
```
$tpl = new TemplateSeed(__DIR__.'/src/templates/');
$tpl->setTemplate('header');
$tpl->params->title = "My Blog";
$tpl->render();
```

Use a template in a subdirectory of the templates path:
```
$tpl->setTemplate('theme/header');
```

Return the template results instead of just echoing it, instead of render() use:
```
$page = $tpl->retrieve();
```

Usage - Caching
--------

The default location for cached version is a cache/ directory within the templates directory. The cache directory must already exist.

Caching enabled from the start:
```
$tpl = new TemplateSeed(__DIR__.'/src/templates/', true);
$tpl->setTemplate('header');
if( ! $tpl->cacheExists() ){
	$tpl->params->title = "My Blog"; 	// Query the DB for this value or other time consuming steps.
}
$tpl->render();
```

Enable caching later:
```
$tpl->enableCaching();      
$tpl->setCacheExpiry(60);     // 1 minute TTL for cached copies.
$tpl->setCachePath();         // Defaults to <template path>/cached or pass in a path.
```

Manually set the cached copy file name (defaults to md5 of template name):
```
$tpl->setCacheKey('hellocached');
```

Template Syntax
--------

A new syntax is not required for TemplateSeed. All parameters - scalar values, arrays, objects, etc - are passed to the template as named with a **tpl_** prefix.

title.tpl.php passed a 'name' string parameter:
```
<h1>Welcome, <?=$tpl_name;?>!</h1>
```

All standard PHP works within the template. Loops, conditionals, etc. Don't forget to escape output when applicable!


Changelog
--------

* v1.0.1 - v1.0.3 - Minor adjustments and documentation tweaks.
* v1.0.0 - Initial release. Under development.