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
* Set global parameters for all templates.
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

Usage - Global parameters
--------

Parameters common to all instances of the template class and thus to all rendered templates can be set.
Beware, per-template params will over-write global params.

On the instance:
```
$tpl->setGlobalParams(['baseurl'=>'/']);
```
Or on the class:
```
TemplateSeed::globalParams(['baseurl'=>'/']);
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

### Template Helpers

A small set of helper functions and variables are also available from within your templates. These are defined simply for convenience and are accessible within a template.

#### $tpl

The $tpl variable is available within the templates and it contains the calling TemplateSeed object.

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
<p>This is about <?=$company;?>.</p>
```

Calling from inside your controller:
```
return $tpl->render(
        'masterpage',
        [
            'page'=>'pages/about',
            'title'=>'About Me',
            'data'=>['company'=>'Acme Co.']
        ]
    );


Changelog
--------

* v1.1.4 - Add global parameters. Clean up code and comments.
* v1.1.3 - Add template helpers.
* v1.1.2 - Add one-line render function to match common frameworks.
* v1.0.4 - Add PHPUnit tests. Change license to MIT. Fix author info.
* v1.0.1 - v1.0.3 - Minor adjustments and documentation tweaks.
* v1.0.0 - Initial release. Under development.
