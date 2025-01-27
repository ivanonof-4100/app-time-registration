<?php
namespace Common\Classes\Controller;

use Exception;
use Common\Classes\LanguagefileHandler;
use Common\Classes\InputHandler;
use Common\Classes\StdApp;
use Common\Classes\CodebaseRegistry;
use Common\Classes\Db\DBAbstraction;
use Common\Classes\Db\MySQLDBAbstraction;
use Common\Classes\Session\FileSessionHandler;
use Common\Classes\Session\DBSessionHandler;
use Common\Classes\Helper\CustomToken;
use Common\Classes\ResponseCode;

/**
 * Filename     : std_controller.class.php
 * Language     : PHP v7.4
 * Date created : 20/02-2023, Ivan
 * Last modified: 24/01-2025, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2025 by Ivan Mark Andersen
 *
 * Description:
 * My standard controller super-class, that every controller-class will inherit from.
 */

// Exception related to this class.
class NoConfigSettingsException extends Exception {};
class NoDBConnectionSettingsException extends Exception {};
class RDBMSNotRunningException extends Exception {};

class StdController
{
  // Attributes
  protected $codebaseRegistry;

  /**
   * Default constructor of the class.
   * @param StdApp $p_appInstance
   */
  public function __construct(StdApp $p_appInstance) {
     $codebaseRegistry = CodebaseRegistry::getInstance();
     $codebaseRegistry->setInstance_appInstance($p_appInstance);
     $this->setInstance_codebaseRegistry($codebaseRegistry);
  }

  /**
   * Destructor of the class.
   */
  public function __destruct() {
  }

  public static function getInstance(string $p_lang =APP_LANGUAGE_IDENT, string $p_charset =APP_DEFAULT_CHARSET, StdApp $p_appInstance) : StdController {
    return new StdController($p_appInstance);
  }

  /**
   * Redirects the web-browser to the given URL.
   * @param string $p_URL
   * @param int $p_responseCode
   * @return void
   */
  public static function redirectBrowser(string $p_URL ='', int $p_responseCode = ResponseCode::HTTP_PAGE_NOT_FOUND) : void {
    // If no HTTP-headers are sent, send one
    if (!headers_sent($pbr_filename, $pbr_lineNumber)) {
      // Send a raw HTTP-header.
      header(sprintf('Location: %s', $p_URL), TRUE, $p_responseCode);
      exit(0);
    } else {
      // This would most likely trigger an error.
      trigger_error(__METHOD__.': Headers was already send', E_USER_WARNING);
      header('Content-type: text/javascript');
      echo "Headers already sent in $pbr_filename on line $pbr_lineNumber\n" .
           "Cannot redirect, for now please click this <a href=\"http://$p_URL\">Redirect link</a> instead\n";
      exit(1);
    }
  }

  /**
   * Makes sure that the perfered supported language is selected, if none used in the URI.
   * @return void
   */
  public function redirectTo_supportedPreferenceLanguage() : void {
    $codebaseRegistry = $this->getInstance_codebaseRegistry();
    $appInstance = $codebaseRegistry->getInstance_appInstance();
    // if ($appInstance instanceof StdApp) {
    $arrSettings = $appInstance->getSettings();
    if (empty($arrSettings['app_lang_supported'])) {
      // Use the defined default-lanugage for the web-application.
      if (defined('APP_LANGUAGE_IDENT')) {
        $uri = sprintf('/%s/', APP_LANGUAGE_IDENT);
        self::redirectBrowser($uri, ResponseCode::HTTP_TEMPORARY_REDIRECT);
      } else {
        // Default language.
        self::redirectBrowser('/da/', ResponseCode::HTTP_TEMPORARY_REDIRECT);
      }
    } else {
      $perferedLanguageIdent = LanguagefileHandler::getDetectedLanguageOfBrowser();
      $langEntryIdent = sprintf('lang_%s', $perferedLanguageIdent);
      if (array_key_exists($langEntryIdent, $arrSettings['app_lang_supported'])) {
        // Then the perfered language of the browser is supported in the web-application.
        $uri = sprintf('/%s/', $perferedLanguageIdent);
        self::redirectBrowser($uri, ResponseCode::HTTP_TEMPORARY_REDIRECT);
      } else {
        if (defined('APP_LANGUAGE_IDENT')) {
          $uri = sprintf('/%s/', APP_LANGUAGE_IDENT);
          self::redirectBrowser($uri, ResponseCode::HTTP_TEMPORARY_REDIRECT);
        } else {
          // Default something.
          self::redirectBrowser('/en/', ResponseCode::HTTP_TEMPORARY_REDIRECT);
        }
      }
    }
  }

  /**
   * Checks if we have an internet-connection at the moment.
   * 
   * @param string $p_hostName Specific host-name Default blank.
   * @return bool Returns boolean TRUE if we have an connection otherwise FALSE.
   */
  public function isThereAConnection(string $p_hostName ='') : bool {
  	 if (empty($p_hostName)) {
       // Make sure that the host-name has a value to use.
       $p_hostName = 'www.google.dk';
  	 }

  	 $hostIP = self::getIPaddressOfHost($p_hostName);
  	 if ($hostIP != $p_hostName) {
       return TRUE;
  	 } else {
       return FALSE;
  	 }
  }

  /**
   * Get the IPv4 address corresponding to a given Internet host-name.
   * 
   * @param string $p_hostName
   * @return string
   */
  public static function getIPaddressOfHost($p_hostName) : string {
  	 return gethostbyname($p_hostName);
  }

  /**
   * Checks to see if the current request was an AJAX-request or not.
   * @return bool Returns TRUE if the current request was an AJAX-request otherwise FALSE.
   */
  public static function isAJAXRequest() : bool {
  	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      return TRUE;
  	} else {
      return FALSE;
  	}
  }

  /**
   * Checks if the required PHP-extension is installed or not. 
   *
   * @param string $p_extensionName
   * @return bool
   */
  public static function isExtensionInstalled($p_extensionName) : bool {
  	 // Get the list of the extensions.
  	 if (!self::getExtensionRelatedFunctions($p_extensionName)) {
       return FALSE;
  	 } else {
       return TRUE;
	   }
  }

  /**
   * @param string $p_methodName
   * @return bool
   */
  public function hasNamedMethod(string $p_methodName) : bool {
    return method_exists($this, $p_methodName);
  }

  /**
   * Gets an array of functions that are related to an extension, if the extension is installed.
   *
   * @param string $p_extensionName
   * @return array|boolean Return boolean FALSE if requested extension is not installed.
   */
  public static function getExtensionRelatedFunctions($p_extensionName) {
  	 return get_extension_funcs($p_extensionName);
  }

  protected function setInstance_codebaseRegistry(CodebaseRegistry $p_codebaseRegistry) : void {
  	 $this->codebaseRegistry = $p_codebaseRegistry;
  }

  /**
   * @return CodebaseRegistry
   */
  public function getInstance_codebaseRegistry() : CodebaseRegistry {
  	 return $this->codebaseRegistry;
  }

  /**
   * @return mixed
   */
  public function getInstance_sessionHandler() {
     $codebaseRegistry = $this->getInstance_codebaseRegistry();
	   if (is_object($codebaseRegistry) && ($codebaseRegistry instanceof CodebaseRegistry)) {
       return $codebaseRegistry->getInstance_sessionHandler();
	   } else {
       trigger_error('It was not possible to retrieve the session-handler object ...', E_USER_ERROR);
	   }
  }

  public static function getDBFormat_ofDateTime() {
	   return MySQLDBAbstraction::getFormat_ofDateTime();
  }

  public static function getDBFormat_ofDate() {
	   return MySQLDBAbstraction::getFormat_ofDate();  	
  }

  /**
   * Checks whether or not the given class has been defined.
   * @return bool
   */
  public static function isClassDeclared($p_className) : bool {
  	 return class_exists($p_className, false);
  }

  /**
   * Returns an array of the loaded settings from app.conf.json
   * @return array
   */
  public function getLoadedSettings() : array {
    $codebaseRegistry = $this->getInstance_codebaseRegistry();
    $appInstance = $codebaseRegistry->getInstance_appInstance();
    if ($appInstance instanceof StdApp) {
      return $appInstance->getSettings();
    } else {
      trigger_error(__METHOD__ .': The appInstance was not an instance of the expected class ...', E_USER_ERROR);
    }
  }

  /**
   * @param array $p_arrSettings
   * @param bool $p_isAPIRequest
   * @throws NoDBConnectionDataException
   * @throws NoConfigSettingsException
   * @throws RDBMSNotRunningException
   * @return void
   */
  public function initDependencies(array $p_arrSettings, bool $p_isAPIRequest =FALSE) : void {
    if (is_array($p_arrSettings)) {
      // Setup database-connection, but first check if RDBMS is running
      if (!MySQLDBAbstraction::isRDBMSRunning()) {
        throw new RDBMSNotRunningException('The RDBMS is NOT running!');
      } else {
        if (array_key_exists('db_connection', $p_arrSettings)) {
          // Use the configuration to connect to the database.
          $dbAbstraction = $this->setupDatabaseConnection($p_arrSettings['db_connection'], TRUE);
        } else {
          throw new NoDBConnectionSettingsException('There was NOT specifyed any settings to connect to the database in the JSON config-file app.conf.json');
        }
      }

      if ($p_isAPIRequest === FALSE) {
        // Setup and start the session.
        if (array_key_exists('session', $p_arrSettings)) {
          $this->setupSession($p_arrSettings['session'], $dbAbstraction);
        } else {
          throw new Exception('There was NOT any settings for session-configuration in the JSON config-file app.conf.json');
        }
      }
    } else {
      throw new NoConfigSettingsException('No settings was defined for the application in the config-file app.conf.json');
    }
  }

  /**
   * @param array $p_arrSettings
   * @return DBAbstraction
   */
  public function setupDatabaseConnection(array $p_arrSettings, $p_resetEntry =FALSE) : DBAbstraction {
    $mySQLDBAbstraction = MySQLDBAbstraction::getInstance($p_arrSettings['host'], $p_arrSettings['dbname'], $p_arrSettings['dbcodepage']);
    if ($mySQLDBAbstraction instanceof DBAbstraction) {
      // Connect to the database using PDO-abstraction.
      $mySQLDBAbstraction->initDatabaseConnection($p_arrSettings['dbuser'], $p_arrSettings['dbpassword']);

      // Set entry
      $codebaseRegistry = $this->getInstance_codebaseRegistry();
      $codebaseRegistry->setInstance_dbConnection($mySQLDBAbstraction);
      return $mySQLDBAbstraction;
    }
  }

  /**
   * @param array $p_arrSettings
   */
  public function setupSession(array $p_arrSettings, DBAbstraction $p_dbAbstraction) {
    $inputHandler = InputHandler::getInstance();
    $arrInputParam_resumeSessId = $inputHandler->retriveInputParameter('_resume_sid', InputHandler::ACCEPTED_DATATYPE_STR, InputHandler::INPUT_SOURCE_POST);
    // Setup and start the session.
    if (isset($p_arrSettings['driver']) && ($p_arrSettings['driver'] == 'db')) {
      // Use the database-based session-handler.
      DBSessionHandler::setupSession($p_arrSettings['expire_secs']);
      $sessionHandler = DBSessionHandler::getInstance($p_dbAbstraction, $p_arrSettings['expire_secs']);
      session_set_save_handler($sessionHandler, TRUE);
    } elseif (isset($p_arrSettings['driver']) && ($p_arrSettings['driver'] == 'file')) {
      if (isset($p_arrSettings['expire_secs'])) {
        FileSessionHandler::setupSession($p_arrSettings['expire_secs']);
        $sessionHandler = FileSessionHandler::getInstance($p_arrSettings['expire_secs']);
      } else {
        // Default something for session.
        FileSessionHandler::setupSession(FileSessionHandler::SESS_LIFETIME_DEFAULT);
        $sessionHandler = FileSessionHandler::getInstance();
      }
    }

    // Start session
    if ($arrInputParam_resumeSessId['is_set'] && $arrInputParam_resumeSessId['is_valid']) {
      // Resume to the session-id that was given.
      // $wasSuccessful = $sessionHandler->start($arrInputParam_resumeSessId['value']);
      $sessionHandler->startWithRegeneration($p_arrSettings['expire_secs']);
    } else {
      if (isset($p_arrSettings['expire_secs'])) {
        $sessionHandler->startWithRegeneration($p_arrSettings['expire_secs']);
      } else {
        $sessionHandler->startWithRegeneration();
      }
    }

    // Make sure that the security-token is set in session.
    if (!isset($_SESSION['security_token'])) {
      // Generate a Security Token
      $customToken = CustomToken::getInstance();
      $sessionHandler->set('security_token', $customToken->getToken());
    }

    // Add the session-handler to the registry
    $codebaseRegistry = $this->getInstance_codebaseRegistry();
    $codebaseRegistry->setInstance_sessionHandler($sessionHandler);
  }

  /**
   * @param StdApp $p_appInstance
   * @return void
   */
  public function setAppInstance(StdApp $p_appInstance) : void {
    $codebaseRegistry = $this->getInstance_codebaseRegistry();
    if ($codebaseRegistry) {
      // Set entry
      $codebaseRegistry->setInstance_appInstance($p_appInstance);
    }
  }

  /**
   * @return StdApp
   */
  public function getAppInstance() : StdApp {
    $codebaseRegistry = $this->getInstance_codebaseRegistry();
    if (($codebaseRegistry)) {
      try {
        // Get entry
        return $codebaseRegistry->getInstance_appInstance();
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }
} // End class