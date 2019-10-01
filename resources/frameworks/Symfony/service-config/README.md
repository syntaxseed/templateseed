# Symfony Framework Helper: Service Config

Autowire TemplateSeed into the DI container for Symfony.

Last Tested For: Symfony v 4.3.4.

## Usage:

- Copy the contents of `app/config/services.php` into your own version of this file in Symfony.
- Copy `app/config/packages/templates.php` into your config directory.
  - Edit configuration settings in above file.
- If you enable caching, you must have a valid cache path.
- Copy `app/src/Factories/TemplateFactory.php` into your own application's corresponding directory.
- You may need to clear the Symfony cache.
- You can now auto-inject the TemplateSeed instance into your controller methods (see example below).

```php
// Controller Method:
public function home(TemplateSeed $tpl)
{
    return new Response(
        $tpl->render('masterpage', [
            'page'=>'pages/index',
            'title'=>'My Homepage',
            'data'=>['name'=>'A value passed to the inner template.']
        ], true)
    );
}
```
