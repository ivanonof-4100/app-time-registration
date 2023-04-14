<?php
namespace Common\Classes\Renderes;

use Smarty;
use Exception;
use TypeError;

/**
 * Script-name  : template.class.php
 * Language     : PHP v7.4+
 * Date created : 26/07-2012, Ivan
 * Last modified: 12/04-2023, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2023 by Ivan Mark Andersen
 *
 * Description:
 *  This class wraps and implements a large subset and maybe in the future all Smarty-template features
 *  in a unique and smart template-handling class using the Smarty-standard.
 *
 *  @see https://www.smarty.net
*/
// Exceptions related to this class.
class TemplateNotFoundException extends Exception {}

class Template
{
   const DEFAULT_CACHE_LIFETIME = 3600;

   private $templateFilename; 
   private $SmartyObj;

   /**
    * Default constructor.
    *
    * @param string $p_templateFilename Default blank.
    * @param string $p_customTemplateDirectory Default blank.
    *
    * @return Template
    */
   public function __construct(string $p_templateFilename ='', string $p_customTemplateDir ='') {
      // Setup the Smarty instance.
      if (!empty($p_customTemplateDir)) {
        $this->setupSmarty($p_customTemplateDir);
      } else {
        $this->setupSmarty();
      }

      // Set the name of the SmartyTemplate.
      $this->setFilename($p_templateFilename);
   }

   /**
    * Default destructor.
    */
   public function __destruct() {
   }

   /**
    * @param string $p_templateFilename
    * @param string $p_customTemplateDir
    * @return Template
    */
   public static function getInstance(string $p_templateFilename, string $p_customTemplateDir ='') : Template {
      return new Template($p_templateFilename, $p_customTemplateDir);
   }

   /**
    * Sets up the Smarty-instance of the Template-instance.
    * @param string $p_customTemplateDir
    */
   protected function setupSmarty(string $p_customTemplateDir =PATH_TEMPLATES_DOMAIN) {
      // Initialize the Smarty-instance.
      $this->SmartyObj = new Smarty();

      // Register the plugin-handler.
      $this->SmartyObj->registerDefaultPluginHandler(array($this, 'myPluginHandler'));

      // Setup the location of the template.
      $this->SmartyObj->setTemplateDir(realpath($p_customTemplateDir .'templates'));
      $this->SmartyObj->setCompileDir(realpath($p_customTemplateDir .'templates_c'));
      $this->SmartyObj->setCacheDir(realpath($p_customTemplateDir .'cache'));
      $this->SmartyObj->setConfigDir(realpath($p_customTemplateDir .'config'));
      $this->SmartyObj->force_compile = TRUE;
 
      // Set debugging-state.
      if (defined('DEBUG')) {
        $this->setDebuggingState(DEBUG);
      } else {
        $this->setDebuggingState(FALSE);  
      }

      // Caching
      $this->turnOnCaching(self::DEFAULT_CACHE_LIFETIME);
   }

   /**
    * Default Plugin Handler
    *
    * Called when Smarty encounters an undefined tag during compilation.
    * 
    * @param string $name Name of the undefined tag.
    * @param string $type Tag type:
    *  Smarty::PLUGIN_FUNCTION
    *  Smarty::PLUGIN_BLOCK
    *  Smarty::PLUGIN_COMPILER
    *  Smarty::PLUGIN_MODIFIER
    *  Smarty::PLUGIN_MODIFIERCOMPILER
    *
    * @param Smarty_Internal_Template $template Template object.
    * @param string &$callback Returned function-name.
    * @param string &$script Optional returned script filepath if function is external.
    * @param bool &$cacheable TRUE by default, set to false if plugin is not cachable (Smarty >= 3.1.8)
    *
    * @return bool Boolean TRUE if successfull
    */
   public function myPluginHandler($name, $type, $template, &$callback, &$script, &$cacheable) {
    switch ($type) {
        case $this->SmartyObj::PLUGIN_FUNCTION:
            switch ($name) {
                case 'scriptfunction':
                    $script = './scripts/script_function_tag.php';
                    $callback = 'default_script_function_tag';
                    return true;
                case 'localfunction':
                    $callback = 'default_local_function_tag';
                    return true;
                default:
                return false;
            }
        case $this->SmartyObj::PLUGIN_COMPILER:
            switch ($name) {
                case 'scriptcompilerfunction':
                    $script = './scripts/script_compiler_function_tag.php';
                    $callback = 'default_script_compiler_function_tag';
                    return true;
                default:
                return false;
            }
        case $this->SmartyObj::PLUGIN_BLOCK:
            switch ($name) {
                case 'scriptblock':
                    $script = './scripts/script_block_tag.php';
                    $callback = 'default_script_block_tag';
                    return true;
                default:
                return false;
            }
        default:
        return false;
     }
   }

   /**
    * Sets the debugging-state of the Smarty-instance.
    * @param boolean $_debuggingState If TRUE Smarty will show an pop-window when displaying the template.
    */
   public function setDebuggingState($p_debuggingState =FALSE) {
      $this->SmartyObj->debugging = (boolean) $p_debuggingState;
   }

   /**
    * Returns the current-path of where to look for the templatess in the filesystem.
    * @return string
    */
   public function getCurrentTemplatePath() {
      $templateDirectoryPath = $this->SmartyObj->getTemplateDir();
      return $templateDirectoryPath[0];
   }

   /**
    * Sets the filename of the template to use.
    * @param string $p_templateFilename
    */
   public function setFilename(string $p_templateFilename) {
      // Set the filename of the Smarty-template.
      $this->templateFilename = (string) $p_templateFilename;
   }

   /**
    * Gets the filename of template to use of the instance.
    * @return string Returns the filename of the smarty-template.
    */
   public function getFilename() : string {
      return $this->templateFilename;
   }

   /**
    * Checks if any given smarty-template exists in the file-system of the server.
    * @return bool Returns a boolean result on whether or not the filename actualy exists in the filesystem of the server.
    */
   public function doesTemplateExists() : bool {
      $templateFilename = $this->getFilename();
      if (empty($templateFilename)) {
        return FALSE;
      } else {
        return $this->SmartyObj->templateExists($templateFilename);
      }
   }

   /**
    * Assigns an value to a template-variable.
    *
    * @param string $p_templateVarName Name of the template variable.
    * @param any $p_templateVarValue The value to set the template-variable.
    */
   public function assign(string $p_templateVarName, $p_templateVarValue) {
      $this->SmartyObj->assign($p_templateVarName, $p_templateVarValue);
   }

   /**
    * Fetches the output of any given template.
    * @throws TemplateNotFoundException
    * @throws Exception
    * @return string Returns the resulting output of template.
    */
   public function fetch() : string {
      if (!$this->doesTemplateExists()) {
        throw new TemplateNotFoundException(sprintf('Smarty-template: <strong>%s</strong> was NOT found in the given file-path <strong>%s</strong>', $this->getFilename(), $this->getCurrentTemplatePath()));
      } else {
        try {
          // Return output of given template.
          return $this->SmartyObj->fetch($this->getFilename());
        } catch (Exception $e) {
          // Re-throw Exception
          throw new Exception(sprintf('Smarty error: <strong>%s</strong>', $e->getMessage()));
        }
      }
   }

   /**
    * Displays the output of any given template.
    * @throws TemplateNotFoundException
    * @return void
    */
   public function display() : void {
      if (!$this->doesTemplateExists()) {
        throw new TemplateNotFoundException(sprintf('Smarty-template: <strong>%s</strong> was NOT found in the given file-path <strong>%s</strong>', $this->getFilename(), $this->getCurrentTemplatePath()));
      } else {
        try {
          // Display the output of the template to use.
          $this->SmartyObj->display($this->getFilename());
        } catch (TypeError $e) {
          // Re-throw Exception
          throw new Exception(sprintf('Smarty error: <strong>%s</strong>', $e->getMessage()));
        }
      }
   }

   /**
    * Turns on the caching and sets the life-time of the cache.
    *
    * @param int $p_cacheLifetimeSecs Default is 3600 seconds which is one hour.
    * @return void
    */
   public function turnOnCaching($p_cacheLifetimeSecs =self::DEFAULT_CACHE_LIFETIME) : void {
      $this->SmartyObj->caching = Smarty::CACHING_LIFETIME_CURRENT;
      /* The cache_lifetime is the length of time in seconds that a template cache is valid.
       * Once this time has expired, the cache will be regenerated.
       */
      $this->SmartyObj->cache_lifetime = (int) $p_cacheLifetimeSecs;
   }

   /**
    * Turns off the caching and clears the entire cache.
    */
   public function turnOffCaching() : void {
      $this->SmartyObj->caching = Smarty::CACHING_OFF;
      $this->clearEntireCache();
   }

   /**
    * Clears the entire cache.
    * @return void
    */
   public function clearEntireCache() : void {
      $this->SmartyObj->clearAllCache();
   }

   /**
    * Clears only the cache-files that has expired.
    * @param int $p_expiredSinceSecAgo The number of seconds since it expired Default is 3600 equal to one hour.
    */
   public function clearExpiredCache(int $p_expiredSinceSecAgo =self::DEFAULT_CACHE_LIFETIME) : void {
      $this->SmartyObj->clearAllCache($p_expiredSinceSecAgo);
   }
} // End class