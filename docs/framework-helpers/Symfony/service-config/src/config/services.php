<?php
use Syntaxseed\Templateseed\TemplateSeed;

$container->autowire(TemplateSeed::class)
    ->setAutoconfigured(true)
    ->setPublic(false)
    ->setArgument('$templatesPath', __DIR__.'/../templates/')
    ->setArgument('$cacheEnabled', false)
    //->setArgument('$cachePath', __DIR__.'/../var/cache/templates/') // Must exist and be writeable.
    ;
