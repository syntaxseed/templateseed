<?php
/* TemplateSeed specific configuration.
 * Not related to Symfony framework.
 * To reference a config value from any other configuration file,
 * wrap the parameter name in two % (e.g. %app.admin_email%).
 */

/*
 * Path to template files (with trailing slash).
 */
$container->setParameter('app.templates.path', __DIR__.'/../../templates/');

/*
 * Whether to turn on caching for all templates by default (true/false).
 */
$container->setParameter('app.templates.cache.enabled', false);

/*
 * TTL in seconds for cached templates. Set to null to use default (1 hour).
 */
$container->setParameter('app.templates.cache.ttl', null);

/*
 * Path to template cache directory (with trailing slash).
 * Set to null to default to <templates.path>/cache/
 * Required if caching is ever used.
 */
$container->setParameter('app.templates.cache.path', __DIR__.'/../../var/cache/templates/');
