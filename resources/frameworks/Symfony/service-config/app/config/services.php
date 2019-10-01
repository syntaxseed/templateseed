<?php
namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Factories\TemplateFactory;
use Syntaxseed\Templateseed\TemplateSeed;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function(ContainerConfigurator $configurator) {

    $services = $configurator->services()
        ->defaults()
        ->autowire()      // Automatically injects dependencies in your services.
        ->autoconfigure() // Automatically registers your services as commands, event subscribers, etc.
    ;

    $services->set(TemplateSeed::class)
        ->factory([TemplateFactory::class, 'createTemplateSeed'])
        ->arg('$templatesPath', '%app.templates.path%')
        ->arg('$cacheEnabled', '%app.templates.cache.enabled%')
        ->arg('$cachePath', '%app.templates.cache.path%')
        ->arg('$cacheExpiry', '%app.templates.cache.ttl%')
    ;

    /*
    // Example without factory (factory needed to set up _asset closure):
    $services->set(TemplateSeed::class)
        ->arg('$templatesPath', __DIR__.'/../templates/')
        ->arg('$cacheEnabled', false)
        ->arg('$cachePath', __DIR__.'/../var/cache/templates/') // Must be set if caching is ever used.
        ->call('setGlobalParams', [[
            'test'=>123
            ]])
    ;
    */
};
