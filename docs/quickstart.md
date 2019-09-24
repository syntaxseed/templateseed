# Quick Start

First ensure you have a directory for your templates. Save them with the **.tpl.php** extension. They can be sorted into subdirectories.

Require with Composer:
```
./composer.phar require syntaxseed/templateseed
```

Import the namespace into your application:
```php
use Syntaxseed\Templateseed\TemplateSeed;
```

Initialize the class with a path to the template files:
```php
$tpl = new TemplateSeed(__DIR__.'/src/templates/');
```

Basic one-line usage (ex returned from a controller or route):
```php
return $tpl->render('header', ['title' => 'Home']);
```

Or, Step-By-Step usage:
```php
$tpl->setTemplate('header');
$tpl->params->title = "My Blog";
return $tpl->retrieve();
```

Use a template in a subdirectory of the templates path:
```php
$tpl->setTemplate('theme/header');
```

To echo the results instead of just returning it, use:
```php
$tpl->output();
```
