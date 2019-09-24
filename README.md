TemplateSeed
=========================

A lightweight, simple PHP templating class with caching and natural PHP template syntax.

Licence: MIT.

Author: Sherri Wheeler sherri.syntaxseed[at]ofitall[dot]com

Features
--------

* PSR-4 autoloading compliant structure.
* Simple to learn and use.
* Template markup language is standard PHP.
* Pass any number of parameters to a template.
* Set global parameters for all templates.
* Small code footprint: integrates easily into your project.
* Simple output caching.
* Supports masterpages (sub-templates).
* Easy to customize.
* Unit-Testing with PHPUnit.

Usage - Quick Start
--------

First ensure you have a directory for your templates. Save them with the **.tpl.php** extension. They can be sorted into subdirectories.

Require with Composer:
```
./composer.phar require syntaxseed/templateseed ^1.2
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

Or, Step-By-Step usage:
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
$tpl->output();
```

Usage - Global parameters
--------

Parameters common to all instances of the template class and thus to all rendered templates can be set.
Beware, per-template params will over-write global params. There are two methods of defining them depending on whether you've instantiated an instance of the class yet or not:

On the instance:
```
$tpl->setGlobalParams(['baseurl'=>'/']);
```
Or, on the class:
```
TemplateSeed::globalParams(['baseurl'=>'/']);
```

Usage - Caching
--------

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


Template Syntax
--------

A new syntax is not required for TemplateSeed. All parameters - scalar values, arrays, objects, etc - are passed to the template as scoped local variables, and templates support native PHP:

If title.tpl.php was passed a 'name' parameter:
```
<h1>Welcome, <?=name;?>!</h1>
```

All standard PHP works within the template. Loops, conditionals, etc. Don't forget to escape output when applicable (you can use the $_ss() 'safe string' helper function)!

### Template Helpers

A small set of helper functions and variables are also available from within your templates. These are defined simply for convenience, prefixed with an underscore, and are accessible within a template.

#### $_tpl

The $_tpl variable is available within the templates and it contains the calling TemplateSeed object.

#### $_ss(string $str);

The $_ss() function is a short alias to the built in htmlspecialchars function, to output 'safe strings'.

#### $_view(string $tplName, array $params = []);

Use the $_view() helper function to include a template from within another template.
Pass it the name of a template file, along with (optional) its own set of parameters.

### Master Pages

Define a master template:

masterpage.tpl.php:
```
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
```
<p>This is all about <?=$company;?>.</p>
```

Calling from inside your controller:
```
return $tpl->render(
        'masterpage',
        [
            'page'=>'pages/about',
            'title'=>'About Our Company',
            'data'=>['company'=>'Acme Co.']
        ], true // Prevent cache of masterpage.
    );
```

**TIP:** When using caching with masterpages (or any kind of template inside a template), you must prevent the master page from being cached by passing true after the params array.

Framework Helpers
--------

A few helpful snippets are provided for specific PHP frameworks. These can be found in the `docs/framework-helpers/` directory of this package.

- Symfony
  - Console commands.
    - Clear cache dir.
    - View cache path.
  - Example service config to auto-wire TemplateSeed.

Changelog
--------

* v1.2.3 - Add Symfony example to wire into Service container.
* v1.2.2 - Add Symfony console commands helpers. Add getCachePath() method.
* v1.2.1 - Per-template over-ride to prevent caching. Required for masterpages.
* v1.2.0 - Attempt to create template cache directory if doesn't exist.
* v1.1.5 - Fix $_tpl helper var. Remove values from tpl scope. Fix readme.
* v1.1.4 - Add global parameters. Clean up code and comments.
* v1.1.3 - Add template helpers.
* v1.1.2 - Add one-line render function to match common frameworks.
* v1.0.4 - Add PHPUnit tests. Change license to MIT. Fix author info.
* v1.0.1 - v1.0.3 - Minor adjustments and documentation tweaks.
* v1.0.0 - Initial release. Under development.
