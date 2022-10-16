<?php
namespace Common\Classes\Controller;

use Exception;
use Common\Classes\CodebaseRegistry;
use Common\Classes\CustomSessionHandler;
use Common\Classes\LanguagefileHandler;
use Common\Classes\InputHandler;
use Common\Classes\OutputBuffer;
use Common\Classes\Db\MySQLDBAbstraction;
use Common\Classes\StdApp;

/**
 * Filename     : std_controller.class.php
 * Language     : PHP v7.4, v7.2, v5.x
 * Date created : 20/02-2014, Ivan
 * Last modified: 27/12-2020, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2014 by Ivan Mark Andersen
 *
 * Description:
 *  My simple standard controller super-class, that every controller-class will inherit from.
 *  @see https://www.php.net/manual/en/reserved.variables.server.php
 *  @see https://www.php.net/manual/en/language.oop5.magic.php
 */
class StdController
{
  const DEFAULT_MIME_FILE = '/etc/mime.types';

  // Attributes
  protected $codebaseRegistry;
  protected $inputHandlerObj;
  protected $languageFileHandlerObj;
  protected $mimeFileName;

  /**
   * Default constructor of the class.
   *
   * @param string $p_lang Default 'da'
   * @param string $p_charset Default 'utf8'
   * 
   * @return StdController
   */
  public function __construct(string $p_lang =APP_LANGUAGE_IDENT, string $p_charset =APP_DEFAULT_CHARSET) {
     $this->setInstance_codebaseRegistry(CodebaseRegistry::getInstance());
     $this->setAttr_inputHandler(InputHandler::getInstance());
     $this->languageFileHandlerObj = LanguagefileHandler::getInstance(FALSE, $p_lang, $p_charset);

     // Initialize dependencies and the registry of the web-app.
     $this->initDependencies();
  }

  /**
   * Destructor of the class.
   */
  public function __destruct() {
  }

  /**
   * @param string $p_fileName
   * @return void
   */
  public function setAttr_mimeFileName(string $p_fileName ='/etc/mime.types') : void {
     $this->mimeFileName = (string) $p_fileName;
  }

  public function getAttr_mimeFileName() : string {
     return $this->mimeFileName;
  }

  protected function setAttr_inputHandler(InputHandler $p_inputHandler) : void {
     $this->inputHandlerObj = $p_inputHandler;
  }

  /**
   * Retrives the requested parameter in specifyed input-source.
   *
   * @param string $p_parameterName
   * @param string $p_expectedDataType
   * @param string $p_parameterSource
   *
   * @return array
   */
  public function retriveInputParameter(string $p_parameterName, string $p_expectedDataType, string $p_parameterSource) {
    if ($p_parameterSource == '_GET') {
      return $this->inputHandlerObj->retriveVarFrom_GET($p_parameterName, $p_expectedDataType);
    }
    elseif ($p_parameterSource == '_POST') {
      return $this->inputHandlerObj->retriveVarFrom_POST($p_parameterName, $p_expectedDataType);
    } else {
      // Default to _GET as parameter-source.
      return $this->inputHandlerObj->retriveVarFrom_GET($p_parameterName, $p_expectedDataType);
    }
  }

  /**
   * @param string $p_langIdent  
   * @param string $p_charset
   *
   * @return LanguagefileHandler
   */
  public function getInstance_languageFileHandler(string $p_langIdent ='da', string $p_charset ='utf8') : LanguagefileHandler {
     if (is_object($this->languageFileHandlerObj) && ($this->languageFileHandlerObj instanceof LanguagefileHandler)) {
       return $this->languageFileHandlerObj;
     } else {
       // Lets make one!
       $this->languageFileHandlerObj = LanguagefileHandler::getInstance(FALSE, $p_langIdent, $p_charset);
       return $this->languageFileHandlerObj;
     }
  }

  /**
   * @return InputHandler
   */
  public function getInstance_inputHandler() : InputHandler {
     return $this->inputHandlerObj;
  }

  /**
   * Redirects the web-browser to the given URL.
   * @param string $p_URL
   */
  public static function redirectBrowser($p_URL) : void {
    // If no HTTP-headers are sent, send one
    if (!headers_sent($pbr_filename, $pbr_lineNumber)) {
      // Send a raw HTTP-header.
      header(sprintf('Location: %s', $p_URL));
      exit(0);
    } else {
      // You would most likely trigger an error here.
      trigger_error(__METHOD__.': Headers was already send', E_USER_WARNING);
      echo "Headers already sent in $pbr_filename on line $pbr_lineNumber\n" .
           "Cannot redirect, for now please click this <a href=\"http://$p_URL\">Redirect link</a> instead\n";
      exit(1);
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
       return TRUE; // We are connected!
  	 } else {
       return FALSE; // We are NOT connected
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
   * Checks if the request-method is GET.
   * @return bool Returns boolean TRUE if the request-method is using GET otherwise FALSE.
   */
  public static function isRequestMethod_GET() : bool {
     if (isset($_SERVER['REQUEST_METHOD'])) {
       return ($_SERVER['REQUEST_METHOD'] == 'GET');
     } else {
       return FALSE;
     }
  }

  /**
   * Checks if the request-method is POST.
   * @return bool Returns boolean TRUE if the request-method is using POST otherwise FALSE.
   */
  public static function isRequestMethod_POST() : bool {
     if (isset($_SERVER['REQUEST_METHOD'])) {
       return ($_SERVER['REQUEST_METHOD'] == 'POST');
     } else {
       return FALSE;
     }
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
   * @param string $p_usedToken Default blank.
   * @return bool Returns boolean TRUE if we have used the correct token.
   * @throws Exception
   */
  public static function isCorrectSecurityToken(string $p_usedToken ='') : bool {
    if (!defined('APP_SECURITY_TOKEN')) {
      throw new Exception('The Security Token for the application is NOT defined ...');
    } else {
      return ($p_usedToken == APP_SECURITY_TOKEN);
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
   * @return mixed CustomSessionHandler OR boolean FALSE on error.
   */
  public function getInstance_sessionHandler() : mixed {
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

  public function initDependencies() : void {
     // Start using buffered-output.
//     $outputBuffer = OutputBuffer::getInstance(FALSE, 'UTF-8', 'UTF-8');
//     $outputBuffer->startOutputBuffering();
//     $codebaseRegistry->setInstance_outputBuffer($outputBuffer);

     $codebaseRegistry = $this->getInstance_codebaseRegistry();

     // Connect to the database if any
     if (defined('SITE_HAS_DB') && (SITE_HAS_DB == TRUE)) {
       // Initialize the database-connection, connect, if defined.
       if (defined('DB_HOST') && defined('DB_NAME')) {
         $this->initDatabaseConnection(TRUE);
       }
     }

     // Start session
     $customSessionHandler = CustomSessionHandler::getInstance();
     $wasSuccessful = $customSessionHandler->activateSession();

     $codebaseRegistry->setInstance_sessionHandler($customSessionHandler);
     // Add the session-instance to the super-global session-array.
     $_SESSION['session_handler'] = $customSessionHandler;
  }

  /**
   * @return void
   */
  public function initDatabaseConnection($p_resetEntry =FALSE) : void {
  	 $codebaseRegistry = $this->getInstance_codebaseRegistry();
     if ((!$codebaseRegistry->doesEntryExists_dbConnection()) || ($p_resetEntry)) {
       try {
         $mySQLDBAbstraction = MySQLDBAbstraction::getInstance(DB_HOST, DB_NAME, DB_CODEPAGE);

         // Connecting to the database using PDO-abstraction.
         $pdoDBConnectionObj = $mySQLDBAbstraction->initDatabaseConnection();

         // Set entry
         $codebaseRegistry->setInstance_dbConnection($mySQLDBAbstraction);
       } catch (Exception $e) {
         echo $e->getMessage();
       }
     }
  }

  /**
   * @param StdApp $p_appInstance
   * @return void
   */
  public function setAppInstance(StdApp $p_appInstance) : void {
    $codebaseRegistry = $this->getInstance_codebaseRegistry();
    if (($codebaseRegistry)) {
      try {
        // Set entry
        $codebaseRegistry->setInstance_appInstance($p_appInstance);
      } catch (Exception $e) {
        echo $e->getMessage();
      }
    }
  }

  public function getAppInstance(){
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

 /**
  * Returns an associative-array containing all MIME-types based on the Apache mime.types file.
  * @return array
  */
  public static function getMIMETypes($p_fileMIMETypes = self::DEFAULT_MIME_FILE) {
     $regex = "/([\w\+\-\.\/]+)\t+([\w\s]+)/i";
     $lines = file($p_fileMIMETypes, FILE_IGNORE_NEW_LINES);
     foreach($lines as $line) {
        if (substr($line, 0, 1) == '#') continue; // skip comments
        if (!preg_match($regex, $line, $matches)) continue; // skip mime types w/o any extensions
        $mime = $matches[1];
        $extensions = explode(" ", $matches[2]);
        foreach($extensions as $ext) {
           $mimeArray[trim($ext)] = $mime;
        } // Each match
     } // Each MIME-line

     return $mimeArray;
  }

  /**
   * @return string|boolean Returns a string of the MIME-type, if not found boolean FALSE.
   */
  public static function getMIMEType_ofFile($p_fileName) : string {
     $arrFilenameParts = pathinfo($p_fileName);
     $arrMIMETypes = self::getMIMETypes(self::DEFAULT_MIME_FILE);
     if (array_key_exists($arrFilenameParts['extension'], $arrMIMETypes)) {
       return $arrMIMETypes[$arrFilenameParts['extension']];
     } else {
       return FALSE;
     }
  }
} // End class