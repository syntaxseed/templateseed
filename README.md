TemplateSeed
=========================

A lightweight PHP templating class with caching and natural PHP template syntax.

Licence: MIT.

Author: Sherri Wheeler sherri.syntaxseed[at]ofitall[dot]com

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

Usage - Quick Start
--------

First ensure you have a directory for your templates. Save them with the **.tpl.php** extension. They can be sorted in to subdirectories.

Require with Composer:
```
./composer.phar require syntaxseed/templateseed ^1.1
```

Import the namespace into your application:
```
use Syntaxseed\Templateseed\TemplateSeed;
```

Initialize the class with a path to the template files:
```
$tpl = new TemplateSeed(__DIR__.'/src/templates/');
```

Basic one-line usage (ex returned from a controller or route):
```
return $tpl->render('header', ['title' => 'Home']);
```

Step-By-Step usage:
```
$tpl->setTemplate('header');
$tpl->params->title = "My Blog";
return $tpl->retrieve();
```

Use a template in a subdirectory of the templates path:
```
$tpl->setTemplate('theme/header');
```

Echo the results instead of just returning it, use:
```
$page = $tpl->output();
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
$tpl->setCacheKey('hellocached');
```

Template Syntax
--------

A new syntax is not required for TemplateSeed. All parameters - scalar values, arrays, objects, etc - are passed to the template as scoped local variables, and templates support native PHP:

If title.tpl.php was passed a 'name' parameter:
```
<h1>Welcome, <?=name;?>!</h1>
```

All standard PHP works within the template. Loops, conditionals, etc. Don't forget to escape output when applicable!


Changelog
--------

* v1.1.2 - Add one-line render function to match common frameworks.
* v1.0.4 - Add PHPUnit tests. Change license to MIT. Fix author info.
* v1.0.1 - v1.0.3 - Minor adjustments and documentation tweaks.
* v1.0.0 - Initial release. Under development.
