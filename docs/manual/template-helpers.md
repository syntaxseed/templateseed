# Template Helpers

A small set of helper functions and variables are also available from within your templates. These are defined simply for convenience, prefixed with an underscore, and are accessible within a template.

## $_tpl

The $_tpl variable is available within the templates and it contains the calling TemplateSeed object.

## $_ss(string $str);

The $_ss() function is a short alias to the built in htmlspecialchars function, to output 'safe strings'.

## $_view(string $tplName, array $params = [], bool $preventCache = false);

Use the $_view() helper function to include a template from within another template.
Pass it the name of a template file, along with (optional) its own set of parameters.
