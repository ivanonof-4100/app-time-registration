<?php
namespace Common\Classes;

// use Common\Classes\CustomAutoloader;
use Common\Classes\CustomRouteHandler;
use Common\Classes\CustomErrorHandler;
use Common\Classes\CustomString;
use Exception;

/**
 * Filename     : std_app.class.php
 * Language     : PHP v7.x
 * Date created : 27/12-2020, Ivan
 * Last modified: 27/09-2022, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2022, 2020 by Ivan Mark Andersen
 *
 * Description:
 *  My standard app class.
 */
class StdApp
{
   const PATH_DELIMITER = '/';

   // Attributes
   /**
    * @var string $pathAppRoot
    */
   protected $pathAppRoot;

   /**
    * @var string $pathCodebaseRoot
    */
   protected $pathCodebaseRoot;

   /**
    * @var string 
    */

   /**
    * @var CustomAutoloader $autoLoader
    */
   protected $autoLoader;

   /**
    * @var CustomRouteHandler $routeHandler
    */
   protected $routeHandler;

   /**
    * @var CustomErrorHandler
    */
   protected $errorHandler;

   public function __construct() {
      // Setup dynamic path-configurations.
      $this->setPath_appRoot();
      $this->setPath_codebaseRoot();
      $this->setPath_logFiles();
      $this->setPath_languageFiles();

      // Setup error-handling.
      $this->setupErrorReporting();

      // Load configuration-file of the app.
      $this->loadAppConfiguration();
      $this->setupErrorHandler();
//      $this->registerAutoloader();
      $this->registerRouteHandler();
   }

   public function __destruct() {
      // $this->autoLoader->__destruct();
      $this->routeHandler->__destruct();
   }

   /**
    * @return StdApp
    */
   public static function getInstance() : StdApp {
      return new StdApp();
   }

   /**
    * @return string
    */
   public static function getPathDelimiter() : string {
      return self::PATH_DELIMITER;
   }

   /**
    * @return string
    */
   public static function getDocumentRoot() : string {
      if (php_sapi_name() == "cli") {
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
      $searchStr = self::PATH_DELIMITER .'app';
      $posFirstOccurrence = $strPath_appRoot->getPosition_firstOccurrence($searchStr);

      // Then find the next directory-delimitor
      $posTruncate = $strPath_appRoot->getPosition_firstOccurrence(self::PATH_DELIMITER, $posFirstOccurrence+1);
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
      $appPathConfig = $pathAppRoot .'config'.self::PATH_DELIMITER;
      return $appPathConfig;
   }

   public function setPath_codebaseRoot() : void {
      $pathAppRoot = $this->getPath_appRoot();
      $this->pathCodebaseRoot = $pathAppRoot. self::PATH_DELIMITER .'my_codebase'. self::PATH_DELIMITER;
   }

   public function getPath_codebaseRoot() : string {
      return $this->pathCodebaseRoot;
   }

   public function setPath_languageFiles() : void {
      $pathCodebaseRoot = $this->getPath_codebaseRoot();
      $this->pathLanguageFiles = $pathCodebaseRoot. self::PATH_DELIMITER .'language'. self::PATH_DELIMITER;
   }

   public function getPath_languageFiles() : string {
      return $this->pathLanguageFiles;
   }

   public function setPath_logFiles() : void {
      $pathAppRoot = $this->getPath_appRoot();
      $this->pathLogFiles = $pathAppRoot .'log'. self::PATH_DELIMITER;
   }

   /**
    * Returns the path to the log-files of application.
    * @return string
    */
   public function getPath_logFiles() : string {
      return $this->pathLogFiles;
   }

   /**
    * Load common settings of the application-setup in config-files for the app.
    * @return void
    */
    public function loadAppConfiguration() : void {
      $pathAppConfig = $this->getPath_appConfigPath();
      try {
        require_once($pathAppConfig .'app.conf.php');
      } catch (Exception $e) {
        echo $e->getMessage();
        exit(2);
      }
   }

   /**
    * Registers the auto-loader that does the auto-loading functionality.
    * @return void
    */
/*
   public function registerAutoloader() : void {
      $this->autoLoader = CustomAutoloader::getInstance();
   }
*/

   /**
    * @return CustomAutoloader
    */
/*
   public function getAttr_autoLoader() : CustomAutoloader {
      return $this->autoLoader;
   }
*/

   public function registerRouteHandler() {
      $this->routeHandler = CustomRouteHandler::getInstance();
   }

   public function getAttr_routeHandler() : CustomRouteHandler {
      return $this->routeHandler;
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
     if (!defined('APP_DEBUG_MODE')) {
       define('APP_DEBUG_MODE', FALSE);
     }

     if (APP_DEBUG_MODE) {
       // Set reported errors in Debug-mode
       self::setPHPConfigParameter('display_startup_errors', '1');
       self::setPHPConfigParameter('track_errors', '1');
       self::setPHPConfigParameter('display_errors', '1');
       self::setPHPConfigParameter('log_errors', '0');
       // Set which errors are reported.
       self::setPHPConfigParameter('error_reporting', E_ALL | E_STRICT | E_WARNING | E_PARSE | E_NOTICE);
       // error_reporting(E_ALL | E_STRICT | E_WARNING | E_PARSE | E_NOTICE);
       // Development Value:  error_reporting(E_ERROR | E_WARNING | E_PARSE);
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
   private function setupErrorHandler() : void {
      $this->errorHandler = CustomErrorHandler::getInstance();
   }

   /**
    * @return void
    */
   public function run() : void {
     $routeHandler = $this->getAttr_routeHandler();
     try {
       // Let the route-handler handle the request.
       $routeHandler->handleRequest($this);
     } catch (Exception $e) {
       trigger_error(sprintf('An error occurred: %s', $e->getMessage()), E_USER_ERROR);
     }
   }
}