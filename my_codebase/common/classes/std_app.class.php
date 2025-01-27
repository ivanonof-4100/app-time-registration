<?php
namespace Common\Classes;

use Common\Classes\CustomErrorHandler;
use Common\Classes\CustomString;
use Common\Classes\JsonConfigReader;
use Common\Classes\RouteHandler;
use Exception;

/**
 * Filename     : std_app.class.php
 * Language     : PHP v7.x
 * Date created : 27/12-2020, Ivan
 * Last modified: 27/09-2022, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2023 by Ivan Mark Andersen
 *
 * Description:
 * My standard app class.
 */
class StdApp
{
   // Attributes
   /**
    * @var array $settings
    */
   protected $settings;

   /**
    * @var string $pathAppRoot
    */
   protected $pathAppRoot;

   /**
    * @var string $pathCodebaseRoot
    */
   protected $pathCodebaseRoot;

   /**
    * @var string $pathLanguageFiles
    */
   protected $pathLanguageFiles;

   /**
    * @var string $pathLogFiles
    */
   protected $pathLogFiles;

   /**
    * @var CustomErrorHandler
    */
   protected $errorHandler;

   /**
    * @var string $languageIdent
    */
   protected $languageIdent;

   public function __construct() {
      // Setup dynamic path-configurations.
      $this->setPath_appRoot();
      $this->setPath_codebaseRoot();
      $this->setPath_logFiles();
      $this->setPath_languageFiles();

      // Load configuration-file of the app.
      $this->loadAppConfiguration();
      // Sets only the default language in case the web-app dont succeed.
      $this->setLanguageIdent(APP_LANGUAGE_IDENT);

      // Setup error-handling.
      $this->setupErrorReporting();
      $this->setupErrorHandler();
   }

   public function __destruct() {
   }

   /**
    * @return StdApp
    */
   public static function getInstance() : StdApp {
      return new StdApp();
   }

   /**
    * Load the bootstrap-file of the application.
    */
   public static function loadAppBootstrap() : void {
      try {
         require_once APP_ROOT_PATH .'bootstrap.php';
      } catch (Exception $e) {
         trigger_error($e->getMessage(), E_USER_ERROR);
         exit(2);
      }
   }

   /**
    * @return string
    */
   public static function getPathDelimiter() : string {
      return DIRECTORY_SEPARATOR;
   }

   /**
    * @return bool
    */
   public static function isCLIMode() : bool {
      return (php_sapi_name() == 'cli');
   }

   /**
    * @return string
    */
   public static function getDocumentRoot() : string {
      if (self::isCLIMode()) {
        // In CLI-mode
        return dirname(__FILE__);
     } else {
        // Not in CLI-mode
        return $_SERVER['DOCUMENT_ROOT'];
     }
   }

   public function setPath_appRoot() : void {
      $strPath_appRoot = CustomString::getInstance(self::getDocumentRoot(), 'UTF-8');
      // The app-directory is the one that has app in it like app-name or just app
      $searchStr = DIRECTORY_SEPARATOR .'app';
      $posFirstOccurrence = $strPath_appRoot->getPosition_firstOccurrence($searchStr);

      // Then find the next directory-delimitor
      $posTruncate = $strPath_appRoot->getPosition_firstOccurrence(DIRECTORY_SEPARATOR, $posFirstOccurrence+1);
      $this->pathAppRoot = $strPath_appRoot->getSubString_truncatedAtLength($posTruncate+1);
   }

   /**
    * @return string
    */
   public function getPath_appRoot() : string {
      return $this->pathAppRoot;
   }

   /**
    * @return string
    */
   public function getPath_appConfigPath() : string {
      $pathAppRoot = $this->getPath_appRoot();
      $appPathConfig = $pathAppRoot .'config'. DIRECTORY_SEPARATOR;
      return $appPathConfig;
   }

   public function setPath_codebaseRoot() : void {
      $pathAppRoot = $this->getPath_appRoot();
      $this->pathCodebaseRoot = $pathAppRoot. DIRECTORY_SEPARATOR .'my_codebase'. DIRECTORY_SEPARATOR;
   }

   public function getPath_codebaseRoot() : string {
      return $this->pathCodebaseRoot;
   }

   public function setPath_languageFiles() : void {
      $pathCodebaseRoot = $this->getPath_codebaseRoot();
      $this->pathLanguageFiles = $pathCodebaseRoot. DIRECTORY_SEPARATOR .'language'. DIRECTORY_SEPARATOR;
   }

   public function getPath_languageFiles() : string {
      return $this->pathLanguageFiles;
   }

   public function setPath_logFiles() : void {
      $pathAppRoot = $this->getPath_appRoot();
      $this->pathLogFiles = $pathAppRoot .'log'. DIRECTORY_SEPARATOR;
   }

   /**
    * Returns the path to the log-files of application.
    * @return string
    */
   public function getPath_logFiles() : string {
      return $this->pathLogFiles;
   }

   /**
    * @param string $p_langIdent
    */
   public function setLanguageIdent(string $p_langIdent =APP_LANGUAGE_IDENT) : void {
      $this->languageIdent = $p_langIdent;
   }

   /**
    * @return string
    */
   public function getLanguageIdent() : string {
      return $this->languageIdent;
   }

   /**
    * Load common settings of the application-setup in config-files for the app.
    * @return void
    */
    public function loadAppConfiguration() : void {
      $pathAppConfig = $this->getPath_appConfigPath();
      $jsonConfigReader = JsonConfigReader::getInstance($pathAppConfig, 'app.conf.json');
      try {
        require_once($pathAppConfig .'app.conf.php');
        // Load the JSON config-file.
        $this->settings = $jsonConfigReader->load();
      } catch (Exception $e) {
        // Re-throw Exception
        throw new Exception($e->getMessage(), $e->getCode());
        exit(2);
      }
   }

   /**
    * @return array
    */
   public function getSettings() : array {
      return $this->settings;
   }

   /**
    * Checks if a given language-ident is supported. 
    * @param string $p_requestedLanguageIdent
    * @return bool
    */
   public function isSupported_requestedLanguageIdent(string $p_requestedLanguageIdent) : bool {
      $settings = $this->getSettings();
      if (!isset($settings['app_lang_supported'])) {
         return FALSE;
      } else {
         $entryLangIdentRequested = sprintf('lang_%s', mb_strtolower($p_requestedLanguageIdent, 'UTF-8'));
         if (is_array($settings['app_lang_supported'])) {
            return array_key_exists($entryLangIdentRequested, $settings['app_lang_supported']);
         } else {
            return FALSE;
         }
      }
   }

   /**
    * @param string $p_configParameter
    * @param mixed $p_configValue
    * 
    * @return void
    */
   protected static function setPHPConfigParameter($p_configParameter, $p_configValue) : void {
    ini_set('"'. $p_configParameter .'"', $p_configValue);
   }

   /**
    * @param string $p_configParameter
    * @return mixed
    */
   public static function getPHPConfigParameter($p_configParameter) {
      return ini_get("'". $p_configParameter ."'");
   }

   /**
    * Sets up the error-reporting for the application.
    * @return void
    */
   public static function setupErrorReporting() : void {
     if (APP_DEBUG_MODE) {
       // Set reported errors in Debug-mode
       self::setPHPConfigParameter('display_startup_errors', '1');
       self::setPHPConfigParameter('track_errors', '1');
       self::setPHPConfigParameter('display_errors', '1');
       self::setPHPConfigParameter('log_errors', '0');
       // Set which errors are reported.
       // self::setPHPConfigParameter('error_reporting', E_ALL | E_STRICT | E_WARNING | E_PARSE | E_NOTICE);
       // Development setting
       self::setPHPConfigParameter('error_reporting', E_ERROR | E_WARNING| E_PARSE);
     } else {
       // Not in debug-mode
       self::setPHPConfigParameter('display_startup_errors','1');
       self::setPHPConfigParameter('track_errors', '1');
       self::setPHPConfigParameter('display_errors','0');
       self::setPHPConfigParameter('log_errors', '1');
       // Report simple running errors
       self::setPHPConfigParameter('error_reporting', E_ERROR | E_STRICT | E_WARNING | E_PARSE | E_NOTICE);
      // Production Value: E_ALL & ~E_DEPRECATED
     }
   }

   /*
    * @return void
    */
   protected function setupErrorHandler() : void {
      $this->errorHandler = CustomErrorHandler::getInstance();
   }

   /**
    * Run method that uses the new generic route-handler to handle every request.
    * @return void
    */
   public function run() : void {
      $routeHandler = RouteHandler::getInstance(APP_ROUTES_PATH);
      $routeHandler->dispatch($this);
   }
}