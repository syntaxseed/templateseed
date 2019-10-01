<?php
namespace App\Factories;

use Syntaxseed\Templateseed\TemplateSeed;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\HttpFoundation\RequestStack;

class TemplateFactory
{
    public static function createTemplateSeed(RequestStack $request_stack, string $templatesPath, bool $cacheEnabled, $cachePath, $cacheExpiry)
    {
        $templateSeed = new TemplateSeed(
            $templatesPath,
            $cacheEnabled,
            $cachePath
        );

        if (!is_null($cacheExpiry)) {
            $templateSeed->setCacheExpiry($cacheExpiry);
        }

        // Set up a callable function that can be used in templates to generate asset urls.
        // Uses Symfony's Asset package.
        $assetsManager =  new PathPackage(
            '/',
            new EmptyVersionStrategy(),
            new RequestStackContext($request_stack)
        );
        $assetCallable = function($assetPath) use ($assetsManager){
            return $assetsManager->getUrl($assetPath);
        };

        // Pass some parameters as accessible in ALL templates.
        $templateSeed->setGlobalParams([
            '_asset'=>$assetCallable
        ]);

        return $templateSeed;
    }
}
