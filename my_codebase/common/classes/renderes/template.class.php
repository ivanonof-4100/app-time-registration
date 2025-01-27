<?php
namespace Common\Classes\Renderes;

use Exception;
use TypeError;
use Smarty;

/**
 * Filename  : template.class.php
 * Language     : PHP v7.4+
 * Date created : 26/07-2012, Ivan
 * Last modified: 21/08-2024, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2024 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * This class wraps and implements a large subset and of all the Smarty-template features.
 * @see https://www.smarty.net
 * @see https://smarty-php.github.io/smarty/
*/
// Exceptions related to this class.
class TemplateNotFoundException extends Exception {}

class Template
{
   const DEFAULT_CACHE_LIFETIME =3600;

   const PATH_TEMPLATES_STD = APP_ROOT_PATH .'view'. DIRECTORY_SEPARATOR .'standard'. DIRECTORY_SEPARATOR;
   const PATH_TEMPLATES_SITE = APP_ROOT_PATH .'view'. DIRECTORY_SEPARATOR .'site'. DIRECTORY_SEPARATOR;
   const PATH_TEMPLATES_MODULE = APP_ROOT_PATH .'view'. DIRECTORY_SEPARATOR .'module'. DIRECTORY_SEPARATOR;

   private $templateFilename; 
   private $smarty;

   /**
    * Default constructor.
    * @param string $p_templateFilename Default blank.
    * @param string $p_customTemplateDirectory Default blank.
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
   protected function setupSmarty(string $p_customTemplateDir =self::PATH_TEMPLATES_SITE) {
      // Initialize Smarty instance.
      $this->smarty = new Smarty();
      $this->smarty->registerClass(Smarty::class, get_class($this->smarty));

      // Setup the location of the template.
      $this->smarty->setConfigDir(realpath($p_customTemplateDir .'config'));
      $this->smarty->setTemplateDir(realpath($p_customTemplateDir .'templates'));
      $this->smarty->setCompileDir(realpath($p_customTemplateDir .'templates_c'));
      $this->smarty->setCacheDir(realpath($p_customTemplateDir .'cache'));
      // My custom plugins will allways be located here.
      $this->smarty->addPluginsDir(realpath(self::PATH_TEMPLATES_STD .'plugins'));
      // Caching
      $this->turnOnCaching(self::DEFAULT_CACHE_LIFETIME);
      $this->smarty->force_compile =TRUE;

      // Register my custom plugins <app-katalog>/view/standard/plugins/
      $this->smarty->registerPlugin(Smarty::PLUGIN_MODIFIER, 'base64_encode', 'base64_encode');
      $this->smarty->registerPlugin(Smarty::PLUGIN_MODIFIER, 'base64_decode', 'base64_decode');

      // Set debugging-state and caching.
      if (defined('APP_DEBUG_MODE')) {
        $this->setDebuggingState(APP_DEBUG_MODE);
      } else {
        // Default to no debugging.
        $this->setDebuggingState(FALSE);
      }
   }

   /**
    * Sets the debugging-state of the Smarty-instance.
    * @param boolean $_debuggingState If TRUE Smarty will show an pop-window when displaying the template.
    */
   public function setDebuggingState(bool $p_debuggingState =FALSE) : void {
      $this->smarty->debugging = (boolean) $p_debuggingState;
   }

   /**
    * Returns the current-path of where to look for the templates in the filesystem.
    * @return string
    */
   public function getCurrentTemplatePath() : string {
      return $this->smarty->getTemplateDir();
   }

   /**
    * Returns an array of template-paths
    * This makes it possible to load other templates from other places.
    * @return array
    */
   public static function getTemplatePaths() : array {
      $arrPaths['standard'] = realpath(self::PATH_TEMPLATES_STD) .DIRECTORY_SEPARATOR.'templates'. DIRECTORY_SEPARATOR;
      $arrPaths['site'] = realpath(self::PATH_TEMPLATES_SITE) .DIRECTORY_SEPARATOR.'templates'. DIRECTORY_SEPARATOR;
      $arrPaths['module'] = realpath(self::PATH_TEMPLATES_MODULE) .DIRECTORY_SEPARATOR.'templates'. DIRECTORY_SEPARATOR;
      return $arrPaths;
   }

   /**
    * Sets the filename of the template to use.
    * @param string $p_templateFilename
    */
   public function setFilename(string $p_templateFilename ='') : void {
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
        return $this->smarty->templateExists($templateFilename);
      }
   }

   /**
    * Assigns an value to a template-variable.
    *
    * @param string $p_templateVarName Name of the template variable.
    * @param any $p_templateVarValue The value to set the template-variable.
    */
   public function assign(string $p_templateVarName, $p_templateVarValue) : void {
      $this->smarty->assign($p_templateVarName, $p_templateVarValue);
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
          return $this->smarty->fetch($this->getFilename());
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
          $this->smarty->display($this->getFilename());
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
   public function turnOnCaching(int $p_cacheLifetimeSecs =self::DEFAULT_CACHE_LIFETIME) : void {
      $this->smarty->caching = Smarty::CACHING_LIFETIME_CURRENT;
      /* The cache_lifetime is the length of time in seconds that a template cache is valid.
       * Once this time has expired, the cache will be regenerated.
       */
      $this->smarty->cache_lifetime = (int) $p_cacheLifetimeSecs;
   }

   /**
    * Turns off the caching and clears the entire cache.
    */
   public function turnOffCaching() : void {
      $this->smarty->caching = Smarty::CACHING_OFF;
      $this->clearEntireCache();
   }

   /**
    * Clears the entire cache.
    * @return void
    */
   public function clearEntireCache() : void {
      $this->smarty->clearAllCache();
   }

   /**
    * Clears only the cache-files that has expired.
    * @param int $p_expiredSinceSecAgo The number of seconds since it expired Default is 3600 equal to one hour.
    */
   public function clearExpiredCache(int $p_expiredSinceSecAgo =self::DEFAULT_CACHE_LIFETIME) : void {
      $this->smarty->clearAllCache($p_expiredSinceSecAgo);
   }

   public static function getSmartyVersion() : string {
      return Smarty::SMARTY_VERSION;
   }
} // End class