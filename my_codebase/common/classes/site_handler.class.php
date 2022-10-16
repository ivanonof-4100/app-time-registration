<?php
/**
 * Filename     : site_handler.class.php
 * Language     : PHP v5.x
 * Date created : 28/10-2012, IMA
 * Last modified: 11/11-2012, IMA
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>

 * Description
 *  This class serves as a nice way to link things about a site together in a professional way.
 *  Things like handling the setup of the environment of the codebase, timezone,
 *  use of language-file-handler, session-handler and custom-error-handler database-connection and so on.
 * 
 *  @example 1: Setup the site-handler instance with a database-connection.
 * 
 *  // Initialize the site-handler instance.
 *  $siteHandler = new SiteHandler('Europe/Berlin', 'da', 'UTF-8');
 *  $siteHandler->setupConfiguration();
 *  $siteHandler->initDatabaseConnection('udvdb.ivanonof.dk', 'employee.fdb', 'WEB_USR_ROLE', 'NONE');
 *
 *  @example 2: Retrieve the language-file-handler instance of the site.
 * 
 *  $siteHandler = SiteHandler::getInstance_siteHandler();
 *  if (is_object($siteHandler)) {
 *    $codebaseRegistry = $siteHandler->getInstance_codebaseRegistry();
 *    $LanguagefileHandler = $codebaseRegistry->getInstance_languageFileHandler();
 *  } else {
 *    $LanguagefileHandler = null;
 *    trigger_error('It was not possible to access the site-handler to get the languagefile-handler ...', E_USER_WARNING);
 *  }
 */

class SiteHandler
{
  // Attributes
  /**
   * $site_id
   * @var int
  */
  protected $site_id;
  
  /**
   * $site_timezone
   * @var string
  */
  protected $site_timezone;

  /**
   * $site_language_ident
   * @var string Eg. 'da' for Danish.
  */
  protected $site_language_ident;

  protected $site_charset;

  protected $site_codebase_path;

  protected $site_codebase_registry;

  // Methods

  /** 
   * Default-constructor
   * @return SiteHandler
   */
  public function __construct($p_timeZone ='', $p_siteLanguageIdent =FALSE, $p_siteCharset =FALSE) {
     // Set default timezone for the site.
     if (empty($p_timeZone)) {
       // Set to default.
       $this->setAttr_site_timezone();
     } else {
       $this->setAttr_site_timezone($p_timeZone);
     }

     // Set language-ident.
     if ($p_siteLanguageIdent) {
       // Use the given parameter
       $this->setAttr_site_language_ident($p_siteLanguageIdent);
     } else {
       // Set using defaults.
       $this->setAttr_site_language_ident();
     }

     // Set use of charset.
     if ($p_siteCharset) {
       $this->setAttr_site_charset($p_siteCharset);
     } else {
       // Use the default.
       $this->setAttr_site_charset();
     }

     mb_internal_encoding($this->getAttr_site_charset());

     // Sets the path to the codebase to the default.
     $this->setAttr_site_codebase_path();

     // Setup the configuration of the site.
     $this->setupConfiguration();
  } // method __construct

  public function __destruct() {  
  } // method __destruct
  
  // Getter and setters 
  /**
   * @param string $p_timeZone Default Blank.
   * @param string|boolean $p_siteLanguageIdent Default boolean FALSE.
   * @param string|boolean $p_siteCharset Default boolean FALSE.
   *
   * @return SiteHandler Returns an initialized instance of SiteHandler
   */
  public static function getInstance($p_timeZone ='', $p_siteLanguageIdent =FALSE, $p_siteCharset =FALSE) {
     if (isset($_SESSION['site_handler'])) {
       // return self::getInstance_siteHandler();
       return new SiteHandler($p_timeZone, $p_siteLanguageIdent, $p_siteCharset);
     } else {
       return new SiteHandler($p_timeZone, $p_siteLanguageIdent, $p_siteCharset);
     }
  } // method getInstance

  /**
   * Returns the site-handler instance stored in the session-array.
   * Pre-condition: The site-handler instance has been stored in the session-array.
   * 
   * @return mixed
   */
  public static function getInstance_siteHandler($p_timeZone ='', $p_siteLanguageIdent =FALSE, $p_siteCharset =FALSE) {
     if (isset($_SESSION['session_handler'])) {
       $sessionHandlerObj = $_SESSION['session_handler'];
       return $siteHandlerObj = $sessionHandlerObj->get('site_handler');
     } else {
//        trigger_error('It was not possible to access the session-handler to get the site-handler object, but one was created ...', E_USER_NOTICE);
       // return self::getInstance('Europe/Berlin', 'da', 'UTF-8');
	     return null;
     }
  } // method getInstance_siteHandler

  protected function setAttr_site_id($p_siteId =0) {
     $this->site_id = (int) $p_siteId;
  } // method setAttr_site_id

  /**
   * @return int
  */
  public function getAttr_site_id() {
     return $this->site_id;
  } // method getAttr_site_id

  /**
   * Sets the time-zone of the site.
   * 
   * @param string $p_timeZone Default is 'Europe/Berlin'.
   * @return boolean
  */
  protected function setAttr_site_timezone($p_timeZone ='Europe/Berlin') {
     $this->site_timezone = (string) $p_timeZone;

     // Set default timezone for the site.
     $isValid_timeZone = date_default_timezone_set($this->getAttr_site_timezone());
     return $isValid_timeZone;
  } // method setAttr_site_timezone

   /**
    * @return string
   */
   public function getAttr_site_timezone() {
      return $this->site_timezone;
   } // method getAttr_site_timezone

   /**
    * Sets the unique identifiying string for the selected language using the ISO 639-1 (2-char language-code). 
    * @param string $p_langIdent Default 'da' for Danish.
    */
   public function setAttr_site_language_ident($p_langIdent ='da') {
      $this->site_language_ident = (string) $p_langIdent;     
   } // method setAttr_site_language_ident

   /**
    * @return string
    */
   public function getAttr_site_language_ident() {
      return $this->site_language_ident;
   } // method getAttr_site_language_ident

   /**
    * Sets what charset to use on the site.
    * @param string $p_defaultCharset Default 'UTF-8'
    */
   protected function setAttr_site_charset($p_defaultCharset ='UTF-8') {
      $this->site_charset = (string) $p_defaultCharset;
   } // method setAttr_site_charset

   /**
    * @return string
    */
   protected function getAttr_site_charset() {
      return $this->site_charset;
   } // method getAttr_site_charset

   public function getCharset() {
      return $this->getAttr_site_charset();
   } // method getCharset

   /**
    * Sets the path to the root of the codebase.
    * @param string $p_codebasePath Default '/var/codebase/'.
   */
   public function setAttr_site_codebase_path($p_codebasePath ='/var/codebase/') {
      $this->site_codebase_path = (string) $p_codebasePath; 
   } // method setAttr_site_codebase_path

   /**
    * Returns the path to the root of the codebase.
    * @return string
    */
   protected function getAttr_site_codebase_path() {
      return $this->site_codebase_path;
   } // method getAttr_site_codebase_path

   /**
    * Returns the path to the root of the codebase.
    * @return string
    */
   public function getPath_codebase() {
      return $this->getAttr_site_codebase_path();
   } // method getPath_codebase

   /**
    * Sets the registry-handler of the site.
    * @param CodebaseRegistry $p_codebaseRegistryObj
    */
   protected function setAttr_site_codebase_registry(CodebaseRegistry $p_codebaseRegistryObj) {
      $this->site_codebase_registry = $p_codebaseRegistryObj;
   } // method setAttr_site_codebase_registry

   /**
    * Returns the instance of the registry of the site. 
    * @return CodebaseRegistry
    */
   protected function getAttr_site_codebase_registry() {
      return $this->site_codebase_registry;
   } // method getAttr_site_codebase_registry

   /**
    * @return CodebaseRegistry
    */
   public function getInstance_codebaseRegistry() {
      return $this->site_codebase_registry;
   } // method getInstance_codebaseRegistry

   public function loadCodebaseConfiguration() {
      $pathCodebaseRoot = $this->getAttr_site_codebase_path();
      require_once($pathCodebaseRoot .'/config/codebase_v2.conf.php');
   } // method loadCodebaseConfiguration

   public static function setupErrorReporting() {
   	  // Just make sure that the constant is defined.
   	  if (!defined('DEBUG')) {
        // If not allready defined in a config-file for the site, then make sure we got something to work with - default to no debuging!
        define('DEBUG', FALSE);
      }

      // Set reporting-level of errors.
      if (DEBUG) {
      	// Debug-mode
        self::setPHPConfigParameter('display_errors', '1');

        // Set which errors are reported.
        self::setPHPConfigParameter('display_startup_errors', '1');
        error_reporting(E_ALL | E_STRICT | E_WARNING | E_PARSE | E_NOTICE);
      } else {
      	// Not in debug-mode
        self::setPHPConfigParameter('display_errors','1');
        self::setPHPConfigParameter('display_startup_errors','1');

        // Report simple running errors
        //  error_reporting(E_ALL | E_STRICT);
        error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

        //  error_reporting(E_ALL | E_STRICT);
        // Development Value:  error_reporting(E_ERROR | E_WARNING | E_PARSE);
        // Production Value: E_ALL & ~E_DEPRECATED
        //  error_reporting(E_ERROR);
        //  error_reporting(E_WARNING);
      }
   } // method setupErrorReporting

   public function setupConfiguration() {
      self::setupErrorReporting();
      // Load configuration of the codebase.
      $this->loadCodebaseConfiguration();

      require_once(PATH_COMMON_CLASSES .'codebase_registry.class.php');
      $this->setAttr_site_codebase_registry(CodebaseRegistry::getInstance());
      $codebaseRegistryObj = $this->getInstance_codebaseRegistry();

      require_once(PATH_COMMON_CLASSES .'custom_errorhandler.class.php');
      $codebaseRegistryObj->setInstance_errorHandler(new CustomErrorHandler());

      // Start output-buffering.
      require_once(PATH_COMMON_CLASSES .'output_buffer.class.php');
      $outputBufferObj = new OutputBuffer();
      $outputBufferObj->startOutputBuffering(FALSE);

      // Set the output-buffer instance in the codebase-registry. 
      $codebaseRegistryObj->setInstance_outputBuffer($outputBufferObj);

      if (DEBUG) {
        require_once(PATH_COMMON_CLASSES .'debug_message_handler.class.php');
        require_once(PATH_COMMON_CLASSES .'input_handler.class.php');
        
        $inputHandlerObj = new InputHandler();
        $arrInputParam_debugLevel = $inputHandlerObj->retriveVarFrom_GET('debug_level', 'pos_int');
        if ($arrInputParam_debugLevel['is_set'] && $arrInputParam_debugLevel['is_valid']) {
          // Use selected debug-level.
          $debugMessageHandlerObj = new DebugMessageHandler($arrInputParam_debugLevel['value']);
        } else {
          // Default to highest debug-level.
          $debugMessageHandlerObj = new DebugMessageHandler(DEBUG_LEVEL_RENDERER);
        }

        $debugMessageHandlerObj->addMessage(DEBUG_LEVEL_CONFIG, __METHOD__ .': Debug is ON!', __FILE__, __LINE__);
        $codebaseRegistryObj->setInstance_debugHandler($debugMessageHandlerObj);
      }

      require_once(PATH_COMMON_CLASSES .'languagefile_handler.class.php');
      $codebaseRegistryObj->setInstance_languageFileHandler(new LanguagefileHandler($this->getAttr_site_language_ident(), FALSE, $this->getAttr_site_charset()));
      $languageFileObj = $codebaseRegistryObj->getInstance_languageFileHandler();
      define('SITE_LANG_IDENT', $languageFileObj->getPOSIXLanguageIdent());

      require_once(PATH_COMMON_CLASSES .'session_handler.class.php');

      // Initialize the current session.
      $sessionHandlerObj = $codebaseRegistryObj->getInstance_sessionHandler();
      if (!is_object($sessionHandlerObj)) {
	      $codebaseRegistryObj->setInstance_sessionHandler(new CustomSessionHandler());
		    $sessionHandlerObj = $codebaseRegistryObj->getInstance_sessionHandler();
	    }

      if (is_object($sessionHandlerObj) && ($sessionHandlerObj instanceof CustomSessionHandler)) {
        // Only start session, if not already started.
        if (!isset($_COOKIE[ini_get('session.name')])) {
          // Start session
          $sessionHandlerObj->start();
		      // Set objects in the session-array for later use.
          $sessionHandlerObj->set('session_handler', $sessionHandlerObj);
        }

		    // Set site-handler in session.
        $sessionHandlerObj->set('site_handler', $this);

/*
		// Only start session, if not already started.
        if (!isset($_COOKIE[ini_get('session.name')])) {

		}
*/
      }

	  if (DEBUG) {
   	  	self::addDebugMessage(__METHOD__ .': Configuration was setup', __FILE__, __LINE__);
   	  }
   } // method setupConfiguration

   /**
    * Initalizes the primary database-connection of the site.
    * 
    * @param int $p_typeOfRDBMS
    * @param string $p_dbHostDomain eg. 'udvdb.ivanonof.dk'
    * @param string $p_dbFileName eg. 'sportsclub-utf8_fb-v2_5.fdb'
    * @param string $p_dbUserRole Default 'WEB_USR_ROLE' other possible values is now 'CMS_USR_ROLE' or 'SYSDBA'.
    * @param string $p_dbCharset Default 'UTF-8'.
    */
   public function initDatabaseConnection($p_typeOfRDBMS, $p_dbHostDomain, $p_dbFileName, $p_dbUserRole ='WEB_USR_ROLE', $p_dbCharset ='UTF-8') {
      $codebaseRegistryObj = $this->getInstance_codebaseRegistry();
      if (!$codebaseRegistryObj->doesEntryExists_dbConnection()) {
        require_once(PATH_COMMON_DB .'std_database_abstraction.class.php');
        // Create database-abstaction instance
        $dbObj = StdDatabaseAbstraction::getInstance($p_typeOfRDBMS, $p_dbHostDomain, $p_dbFileName, $p_dbCharset);
        if (($dbObj instanceof StdDatabaseAbstraction) && $dbObj->isRDBMSRunning()) {
          try {
            // Open connection to the database-file via the RDBMS.
            $dbObj->connect(DB_SYSDBA_USR, DB_SYSDBA_PASSWD, $p_dbUserRole);

            // Set primary database-connection of the registry.
            $codebaseRegistryObj->setInstance_dbConnection($dbObj);
            $this->storeSiteHandler_inSession();
            if (DEBUG) {
              $debugText = __METHOD__ .': We got an open database-connection: <b>'. $dbObj->getDBInfo() .'</b>';
              $debugMessageHandlerObj = $codebaseRegistryObj->getInstance_debugHandler();
              $debugMessageHandlerObj->addMessage(DEBUG_LEVEL_CONFIG, $debugText, __FILE__, __LINE__);
            }
          } catch (DBConnectErrorException $e) {
            echo 'We dont have an open database-connection ...'. PHP_EOL .$e->getMessage();
            exit(2);
          }
        } else {
          trigger_error('The database-server is NOT running!'.PHP_EOL .' Please contact the system-administrator or web-master of the site ('. $_SERVER['SERVER_ADMIN'].') ...', E_USER_ERROR);
          exit(1);
        }
      }
   } // method initDatabaseConnection

   protected function storeSiteHandler_inSession() {
      $codebaseRegistryObj = $this->getInstance_codebaseRegistry();
      if (isset($codebaseRegistryObj) && ($codebaseRegistryObj instanceof CodebaseRegistry)) {
        $sessionHandlerObj = $codebaseRegistryObj->getInstance_sessionHandler();
        $sessionHandlerObj->set('site_handler', $this);
      }
   } // method storeSiteHandler_inSession

   public static function loadConfiguration_forDomainSite() {
      // Check if its allready loaded before doing the load once again.
      if (!self::isAllreadyConfigured()) {
        $siteDomainConfig = self::getPath_codebaseRoot() .'config/'. self::getFilename_forSiteConfigurationFile();

        require_once(self::getPath_codebaseRoot() .'/common/classes/file_handler.class.php');
		    if (FileHandler::isRegularFile($siteDomainConfig)) {
          try {
          	// Try access the config-file.
          	require_once($siteDomainConfig);
          } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), PHP_EOL;
          }
		    } else {
		      trigger_error('Unable to load the site-configuration file ('. var_export($siteDomainConfig, TRUE) .') ...', E_USER_WARNING);	
		    }
      }
   } // method loadConfiguration_forDomainSite

   /**
    * Returns the name of the configuration-file of the site of the domain.
    * @return string Eg.: site.ivanonof_dk.conf.php
    */
   public static function getFilename_forSiteConfigurationFile() {
      require_once(self::getPath_codebaseRoot() .'classes/string.class.php');
   	  require_once(self::getPath_codebaseRoot() .'classes/file_handler.class.php');

      // Find out the name of the config-file of the domain.
      $pathDocumentRoot = self::getPath_documentRoot();

   	  $strObj_confFile = new String('UTF-8', String::trimWhitespaces(FileHandler::tidyFilename($pathDocumentRoot)));
   	  $strObj_confFile->toLowercase();
   	  $strObj_confFile->setAttr_str($strObj_confFile->getSubString($strObj_confFile->getAttr_str(), 6));

   	  // Replace '.' with '_' underscore.
   	  $strObj_confFile->doReplacement('.', '_');
   	  $strObj_confFile->setAttr_str(sprintf('site.%s.conf.php', $strObj_confFile->getAttr_str()));

   	  return $strObj_confFile->getAttr_str();
   } // method getFilename_forSiteConfigurationFile

   /**
    * Returns the root-path of where the codebase is installed in the file-system of the server.
    * @return string
    */
   public static function getPath_codebaseRoot() {
   	  // TODO: Should be stored in a XML-file or a installation-database.
   	  return '/var/demo_codebase/';
   } // method getPath_codebaseRoot

   public static function getPath_documentRoot() {
   	  return $_SERVER['DOCUMENT_ROOT'];
   } // method getPath_documentRoot

   /**
    * Checks if the configuration has allready been done.
    * @return boolean Returns TRUE if site has allready been configured otherwise FALSE.
    */
   public static function isAllreadyConfigured() {
      $siteHandlerObj = self::getInstance_siteHandler();
      if ($siteHandlerObj instanceof SiteHandler) {
        return TRUE;
      } else {
        return FALSE;
      }
   } // method isAllreadyConfigured

   /**
    * @param string $p_configParameter
    * @param mixed $p_configValue
    * 
    * @return void
    */
   protected static function setPHPConfigParameter($p_configParameter, $p_configValue) {
   	  ini_set('"'. $p_configParameter .'"', $p_configValue);
   } // method setPHPConfigParameter

   /**
    * @param string $p_configParameter
    * @return mixed
    */
   public static function getPHPConfigParameter($p_configParameter) {
   	  return ini_get("'". $p_configParameter ."'");
   } // method getPHPConfigParameter

   /**
    * Adds debug-messages during code-execution for config-classes.
    * 
    * @param string $p_debugText
    * @param string $p_filename Optional use __FILE__ default blank.
    * @param int|boolean $p_lineNumber Optional use __LINE__ default FALSE.
    */
   protected static function addDebugMessage($p_debugText, $p_filename ='', $p_lineNumber =FALSE) {
      $siteHandlerObj = self::getInstance_siteHandler();
      if (isset($siteHandlerObj)) {
        $codebaseRegistryObj = $siteHandlerObj->getInstance_codebaseRegistry();
        $debugHandlerObj = $codebaseRegistryObj->getInstance_debugHandler();
		    if ($debugHandlerObj instanceof DebugMessageHandler) {
          // Add debug-message.
          $debugHandlerObj->addMessage(DEBUG_LEVEL_CONFIG, $p_debugText, $p_filename, $p_lineNumber);
		    } else {
          trigger_error('The DebugHandler instance was not of the expected data-type (type was '.  get_class($debugHandlerObj) .')', E_USER_ERROR);
		    }
      } else {
        trigger_error('It was not possible to access the site-handler instance ...', E_USER_ERROR);
      } 
   } // method addDebugMessage
} // End class