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


Usage
--------

Require with Composer:
```
"require": {
        "syntaxseed/templateseed": "*"
}
```

Import the namespace into your application:
```
use syntaxseed\templateseed\TemplateSeed;
```

Basic Usage:
```
$tpl = new TemplateSeed(__DIR__.'/src/templates/');
$tpl->setTemplate('header');
$tpl->params->title = "My Blog";
$tpl->render();
```