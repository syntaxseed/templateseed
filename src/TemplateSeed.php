<?php 
namespace Syntaxseed\Templateseed;

/**
  * AvTemplate - PHP Templating class.
  * @author Sherri Wheeler
  * @version  1.00
  * @copyright Copyright (c) 2009-2018, Sherri Wheeler - Avinus - syntaxseed.com
  * Usage:
  *   require("avtemplate.class.php");
  *   $tpl = new Template('/path/to/templates/');
  *   $tpl->setTemplate('header');
  *   $tpl->params->debug = $debug;
  *   $tpl->params->title = "Home";
  *   $tpl->render();
  * You might want a cron to clear out the cache directory periodically if you use caching.   
  */
class TemplateSeed{

    private $cache = false;         // Whether or not to use caching.
    private $cacheKey = null;       // The name of the file to cache to (supplied, or md5 of template name).
    private $cachePeriod = 3600;    // TTL for cached templates in seconds (3600=1hr).
    private $cachePath = null;      // Path to the cached files.
    private $templateFile = null;   // Full path and file name of the template file.
    private $templatesPath = '';    // Path to the templates directory.
    private $templateOutput ='';    // The generated (or cached) template output.
    public $params = null;          // The parameters to be passed into the template.

    /**
    * Initialize the template object.
    *
    * @param string $templateRootDir (required) Root path to the templates directory. Must be readable.
    * @param string $cacheEnabled (optional) Whether to use caching. Defaults to false.
    * @param string $templateRootDir (required) Root path to the templates directory. Must be readable.
    *
    * @return string
    */
    public function __construct($templatePath, $cacheEnabled=false, $cachePath=null)
    {
        $this->cache = boolval($cacheEnabled);
        $this->templatesPath = $templatePath;
        if( !is_readable($this->templatesPath) ){
            $this->error("Template Root Path ({$this->templatesPath}) does not exist or not readable.");
        }
        if($this->cache){
            if(empty($cachePath)){
                $this->setCachePath($this->templatesPath.'cache/');  // IMPORTANT: Directory must already exist.
            }else{
                $this->setCachePath($cachePath);
            }            
        }else{
            $this->cachePath = null;
        }
        $this->params = new \StdClass;
    }
  
    /* Set the name of the template to be used. Should not include file extension. */
    public function setTemplate($tpl, $clearParams=true){
        if($clearParams){
            $this->clearParams();
        }
        $this->templateFile = $this->templatesPath.$tpl.'.tpl.php';
    }

    /* Clear any template parameters that have been set. */
    private function clearParams(){
        $this->params = new \StdClass;
    }
    
    /* Write the generated (or cached) template contents to standard output (browser). */
    public function render(){
        $this->generateOutput();
        echo($this->templateOutput);
    }
  
    /* Return the generated (or cached) template contents. */
    public function retrieve(){
        $this->generateOutput();
        return($this->templateOutput);
    }
  
    /* Assign the generated template to the $this->templateOutput property. If we have a cached version that is not expired use that.
     * If we are using caching and don't have a cached version, cache it. */
    private function generateOutput(){
        if(!file_exists($this->templateFile)){
            $this->error("Template File ({$this->templateFile}) not found.");
        }

        $gotCached = false;    
        // Should we used a cached version?
        if($this->cache && $this->cacheExists()){
            $this->templateOutput = $this->getCache();
            if($this->templateOutput !== false){
                $gotCached = true;
            }
        }
    
        if(!$gotCached){    
            extract(get_object_vars($this->params), EXTR_REFS | EXTR_PREFIX_ALL, 'tpl');        
            ob_start();
            include($this->templateFile);
            $this->templateOutput = ob_get_clean();

            // Should we (re)cache this template?  
            if( $this->cache ){
                $cacheFile = $this->getCacheFile();            
                file_put_contents($cacheFile, $this->templateOutput);
            }  
        }
    }    
  
    /* Exit the application with an error message. */
    private function error($message = ''){
        if(empty($message)){
            $message = 'A fatal error occured. Unable to continue.';
        }
        throw new \Exception("TemplateSeed Error: {$message}");
    }
  
  
    // ******** Caching Methods ***************
  
    public function enableCaching($ttl = 3600, $key = null){
        $this->cache = true;
        if(!is_null($key)){
            $this->cacheKey = $key;
        }
        $this->cachePeriod = $ttl;
    }
   
    public function disableCaching(){
        $this->cache = false;   
    }
   
    public function setCachePath($path){
        $this->cachePath = $path;
        if( !is_writeable($this->cachePath) ){
            $this->error("Cache Path ({$this->cachePath}) not writeable.");
        } 
    }

    /* Check whether a cache file exists and is not expired. */
    public function cacheExists(){
        $cacheFile = $this->getCacheFile();
        if(file_exists($cacheFile) && filemtime($cacheFile) > (time()-$this->cachePeriod) ){
            return(true);
        }else{
            return(true);
        }
    }
   
    /* Get the contents of a cache file if it exists and is not expired. */
    public function getCache(){
        if($this->cache && $this->cacheExists()){
            return(file_get_contents($this->getCacheFile()));
        }else{
            return(FALSE);
        }
    }

    /* Get the name of the file this template should be cached to. Using the supplied key, or the md5 of the template name. */
    private function getCacheFile(){
        if(is_null($this->cachePath) || !file_exists($this->cachePath)){
            $this->error('Invalid Cache Path. '.$this->cachePath);   
        }
        $cacheKey = is_null($this->cacheKey) ? md5($this->templateFile) : $this->cacheKey;
        return($this->cachePath . '/' . $cacheKey);
    }
}