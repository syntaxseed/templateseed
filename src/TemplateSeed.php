<?php
namespace Syntaxseed\Templateseed;

/**
  * -------------------------------------------------------------
  * TemplateSeed - Simple PHP Templating class.
  * -------------------------------------------------------------
  * @author Sherri Wheeler
  * @version  1.2.0
  * @copyright Copyright (c) 2019, Sherri Wheeler - syntaxseed.com
  * @license MIT
  *
  * Usage:
  *   require("TemplateSeed.php");
  *   $tpl = new Template('/path/to/templates/');
  *
  *   // One-line Method (return from your controller or route):
  *   return $tpl->render('index', ['debug' => $debug, 'title' => 'Home']);

  *   // ...or Long Method:
  *   $tpl->setTemplate('header');
  *   $tpl->params->debug = $debug;
  *   $tpl->params->title = "Home";
  *   $tpl->output();
  *
  * You might want a cron to clear out the cache directory periodically if you use caching.
  */

class TemplateSeed
{
    public $params = null;                // The parameters to be passed into the template.
    public static $staticParams = null;   // The parameters common to ALL instances of this class. Like global params.

    private $cache = false;               // Whether or not to use caching.
    private $cacheKey = null;             // The name of the file to cache to (supplied, or md5 of template name).
    private $cachePeriod = 3600;          // TTL for cached templates in seconds (3600=1hr).
    private $cachePath = null;            // Path to the cached files.
    private $templateFile = null;         // Full path and file name of the template file.
    private $templatesPath = '';          // Path to the templates directory.
    private $templateOutput ='';          // The generated (or cached) template output.

    /**
    * Initialize the template object.
    *
    * @param string $templatesPath (required) Root path to the templates directory. Must be readable.
    * @param string $cacheEnabled (optional) Whether to use caching. Defaults to false.
    * @param string $cachePath (required) Root path to the cache directory. Must be readable & writeable.
    *
    * @return void
    */
    public function __construct($templatesPath, $cacheEnabled=false, $cachePath=null)
    {
        $this->params = new \StdClass;
        if (is_null(self::$staticParams)) {
            self::init();
        }

        $this->setTemplatesPath($templatesPath);

        $this->cache = boolval($cacheEnabled);
        $this->setCachePath($cachePath);
    }

    /**
    * Initialize the static properties
    *
    * @return void
    */
    public static function init()
    {
        self::$staticParams = new \StdClass;
    }

    /**
    * Set the static parameters global to all template objects.
    *
    * @return void
    */
    public function setGlobalParams($params = [])
    {
        self::globalParams($params);
    }

    /**
    * Static method to set parameters global to all template objects.
    *
    * @return void
    */
    public static function globalParams($params = [])
    {
        self::$staticParams = (object) $params;
    }

    /**
     * Set the template name, assign params array and return rendered output in one method.
     * Call this function in routes and controllers.
     *
     * @param string $tpl
     * @param array $params
     * @return string
     */
    public function render($tpl, $params = [])
    {
        $this->setTemplate($tpl);
        $this->params = (object) $params;
        return $this->retrieve();
    }

    /**
     * Set the name of the template to be used. Should not include file extension.
     *
     * @param string $tpl
     * @param boolean $clearParams
     * @return void
     */
    public function setTemplate($tpl, $clearParams=true)
    {
        if ($clearParams) {
            $this->clearParams();
        }
        $this->templateFile = $this->templatesPath.$tpl.'.tpl.php';
    }

    /**
     * Set the path to the templates directory.
     *
     * @param string $templatesPath
     * @return void
     */
    private function setTemplatesPath($templatesPath)
    {
        $this->templatesPath = $templatesPath;
        if (!is_readable($this->templatesPath)) {
            $this->error("Template Root Path ({$this->templatesPath}) does not exist or not readable.");
        }
    }


    /**
     * Clear any template parameters that have been set.
     *
     * @return void
     */
    private function clearParams()
    {
        $this->params = new \StdClass;
    }

    /**
     * Write the generated (or cached) template contents to standard output (browser).
     *
     * @return void
     */
    public function output()
    {
        $this->generateOutput();
        echo($this->templateOutput);
    }

    /**
     * Return the generated (or cached) template contents.
     *
     * @return string
     */
    public function retrieve()
    {
        $this->generateOutput();
        return($this->templateOutput);
    }

    /**
     * Assign the generated template to the $this->templateOutput property.
     * If we are using caching and don't have a cached version, cache it.
     * If we have a cached version that is not expired use that.
     *
     * @return void
     */
    private function generateOutput()
    {
        if (!file_exists($this->templateFile)) {
            $this->error("Template File ({$this->templateFile}) not found.");
        }

        if (!$this->useCache()) {
            try {
                ob_start();
                $this->protectedInclude();
                $this->templateOutput = ob_get_clean();
            } catch (\Throwable $ex) { // PHP 7+
                ob_end_clean();
                throw $ex;
            } catch (\Exception $ex) { // PHP < 7
                ob_end_clean();
                throw $ex;
            }

            // (Re)cache this template if applicable.
            $this->setCache();
        }
    }

    /**
     * Wrap our template include into a method scope.
     * This allows our template to have access only to the template params.
     * It also prevents collisions with the global namespace.
     *
     * This function also defines some template helpers:
     *   $_ss(string $str) - Safe String. Html encode the string.
     *   $_view(string $tplName, array $params) - include another template into the current one.
     *
     * @return void
     */
    private function protectedInclude()
    {
        $_tpl = $this;

        // View Helpers:
        // * Include another view into the current view:
        $_view = function ($tplName, $params = []) use ($_tpl) {
            $tplCopy = clone $_tpl; // Don't want to mess with the calling template's settings.
            echo($tplCopy->render($tplName, $params));
            unset($tplCopy);
        };
        // * Encode html for a 'Safe String'.
        $_ss = function ($str) {
            return htmlspecialchars($str, ENT_QUOTES);
        };

        // Extract template parameters into this local namespace.
        $allParams = (object) array_merge((array)self::$staticParams, (array)$this->params);
        extract(get_object_vars($allParams));
        unset($allParams);

        // Included template has access to variables local to this function.
        include($this->templateFile);
    }

    /**
     * Exit the application by throwing an error message.
     *
     * @param string $message
     * @return void
     */
    private function error($message = '')
    {
        if (empty($message)) {
            $message = 'A fatal error occured. Unable to continue.';
        }
        throw new \Exception("TemplateSeed Error: {$message}");
    }


    // *************** Caching Methods ***************

    /**
     * Turn on caching.
     * Requires cache path to be previously set!
     *
     * @return void
     */
    public function enableCaching()
    {
        $this->cache = true;
    }

    public function disableCaching()
    {
        $this->cache = false;
    }

    /**
     * Determine if we can use a cached version of the template and if so, fetch it.
     *
     * @return bool
     */
    protected function useCache()
    {
        // Should we use a cached version?
        if ($this->cache && $this->cacheExists()) {
            $this->templateOutput = $this->getCache();
            if ($this->templateOutput !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Manually set a name for the cached file instead of using the md5 of the template name.
     *
     * @param string $key
     * @return void
     */
    public function setCacheKey($key)
    {
        $key = preg_replace('/[^\da-z]/i', '', $key);
        if (!empty($key)) {
            $this->cacheKey = $key;
        }
    }

    /**
     * Set the TTL for the cached template in seconds.
     *
     * @param integer $ttl
     * @return void
     */
    public function setCacheExpiry($ttl = 3600)
    {
        $this->cachePeriod = abs(intval($ttl));
    }

    /**
     * Set the path to the cached template files.
     * Defaults to a cache/ subdirectory under the templates dir.
     * Creates directory if doesn't exist.
     *
     * @param string $cachePath
     * @return void
     */
    public function setCachePath($cachePath='')
    {
        if ($this->cache) {
            if (empty($cachePath)) {
                $this->cachePath = $this->templatesPath.'cache/';
            } else {
                $this->cachePath = $cachePath;
            }

            if (!file_exists($this->cachePath)) {
                if (!@mkdir($this->cachePath, 0775)) {
                    $this->error("Attempt to create template cache path ({$this->cachePath}) failed.");
                }
            }

            if (!is_readable($this->cachePath) || !is_writeable($this->cachePath) || !is_dir($this->cachePath)) {
                $this->error("Template cache path ({$this->cachePath}) does not exist or is not accessible & writeable.");
            }
        } else {
            $this->cachePath = null;
        }
    }

    /**
     * Check whether a cache file exists and is not expired.
     *
     * @return bool
     */
    public function cacheExists()
    {
        $cacheFile = $this->getCacheFile();
        if (file_exists($cacheFile) && filemtime($cacheFile) > (time()-$this->cachePeriod)) {
            return(true);
        } else {
            return(false);
        }
    }

    /**
     * Cache the template if applicable.
     *
     * @return void
     */
    protected function setCache()
    {
        if ($this->cache) {
            $cacheFile = $this->getCacheFile();
            file_put_contents($cacheFile, $this->templateOutput);
        }
    }

    /**
     * Get the contents of a cache file if it exists and is not expired.
     *
     * @return string|false
     */
    public function getCache()
    {
        if ($this->cache && $this->cacheExists()) {
            return(file_get_contents($this->getCacheFile()));
        } else {
            return(false);
        }
    }

    /**
     * Get the name of the file this template should be cached to.
     * Using the supplied key, or the md5 of the template name.
     *
     * @return string|false
     */
    private function getCacheFile()
    {
        if (is_null($this->cachePath) || !file_exists($this->cachePath)) {
            $this->error('Invalid Cache Path. '.$this->cachePath);
            return false;
        }
        $cacheKey = is_null($this->cacheKey) ? md5($this->templateFile) : $this->cacheKey;
        return($this->cachePath . $cacheKey);
    }
}
