# Template Syntax

A new syntax is not required for TemplateSeed. All parameters - scalar values, arrays, objects, etc - are passed to the template as scoped local variables, and templates support native PHP:

If title.tpl.php was passed a 'name' parameter:
```php
<h1>Welcome, <?=name;?>!</h1>
```

All standard PHP works within the template. Loops, conditionals, etc. Don't forget to escape output when applicable (you can use the `$_ss()` 'safe string' helper function)!
