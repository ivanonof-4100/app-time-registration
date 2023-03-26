<?php
namespace Common\Classes;

use Throwable;
use Exception;
use ErrorException;
use Common\Classes\Controller\ErrorController;
use Common\Classes\FileHandler;
use Common\Classes\Datetime\CustomDateTime;

/**
 * Filename     : custom_errorhandler.class.php
 * Language     : PHP 7.x
 * Date created : 14/04-2012, Ivan
 * Last modified: 06/05-2012, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * 
 * Description:
 *  Its good practice to develop robust and error-tolerant web-applications.
 * 
 *  This is my custom error-handler that handles standard PHP-errors like any unhandled errors.
 *  Depending on the errorDisplayMode it can either log errors or display them when they occur.
 *  And depending on the type of the error that occurs we can selective do the correct thing.
*/

// Exceptions related to this class.

// Make sure the PHP error-settings is set.
$arrErrIni = array(
	'display_errors' => 'On',
	'display_startup_errors' => 'On',
	'log_errors' => 'On',
   'track_errors' => 'On',
	'html_errors' => 'Off',
);
foreach ($arrErrIni as $iniKey => $iniVal) {
  ini_set($iniKey, $iniVal);
}

// Try to ensure working on older PHP versions
$_defines = array('E_RECOVERABLE_ERROR' => 4096, 'E_DEPRECATED' => 8192, 'E_USER_DEPRECATED' => 16384);
foreach ($_defines as $_errname => $_errvalue) {
  if (!defined($_errname)) {
    define($_errname, $_errvalue);
  }
}

class CustomErrorHandler
{
  private $stdErrorHandler;

  /**
   * @var string $errorDisplayMode Possible values are 'display' or 'log'.
  */
  private $errorDisplayMode;

  private $defaultExceptionHandler;
  private $siteLogfile = APP_LOGFILE;

  /**
   * Error code to readable string mappings
   * @var array
   */
  private $errorTypes = array(
		E_PARSE => 'Parsing Error',
		E_ALL => 'All errors occurred at once',
		E_WARNING => 'Warning',
		E_CORE_WARNING => 'Core Warning',
		E_COMPILE_WARNING => 'Compile Warning',
		E_USER_WARNING => 'User Warning',
		E_ERROR => 'Error',
		E_CORE_ERROR => 'Core Error',
		E_COMPILE_ERROR => 'Compile Error',
		E_USER_ERROR => 'User Error',
		E_RECOVERABLE_ERROR => 'Recoverable error',
		E_NOTICE => 'Notice',
		E_USER_NOTICE => 'User Notice',
		E_DEPRECATED => 'Deprecated',
		E_USER_DEPRECATED => 'User Deprecated',
		E_STRICT => 'Strict Error');

  /**
   * Default constructor
   * @return CustomErrorHandler
  */
  public function __construct() {
    // Make sure the PHP error-settings is set.
    // Set the custom error-handler.
    $this->stdErrorHandler = set_error_handler(array($this, 'exceptionErrorHandler'));
    register_shutdown_function(array($this, 'shutdownHandler'));

    if (ini_get('display_errors')) {
      $this->errorDisplayMode = 'display';
    } else {
      $this->errorDisplayMode = 'log';
    }

    ini_set('track_errors', '1');
    ini_set('error_log', $this->getAttr_siteLogfile());

    // Sets the default exception-handler for handling unexpected errors.
    $this->defaultExceptionHandler = set_exception_handler(array($this, 'handleException'));
  }

  public function __destruct() {
    // Restore the original error-handler of PHP.
    restore_error_handler();
    restore_exception_handler();
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
   * @return CustomErrorHandler
   */
  public static function getInstance() : CustomErrorHandler {
     return new CustomErrorHandler();
  }

  public function getAppInstance() : StdApp {
     return StdApp::getInstance();
  }

  /**
   * @return string Returns 'display' or 'log'
  */
  protected function getAttr_errorDisplayMode() {
     return $this->errorDisplayMode;
  }
 
  private function getAttr_siteLogfile() {
     return $this->siteLogfile;
  }

  public function setTopLevel_exceptionHandler($p_exceptionObj) : void {
  	  $errorMessage = sprintf("Error: %s", $p_exceptionObj->getMessage());
	  $arrLastError = error_get_last();
     $this->displayLastError($arrLastError);
  }

  /**
   * @param int $p_errNo
   * @param string $p_errStr
   * @param string $p_errFile
   * @param string $p_errLine
   *
   * @throws ErrorException
  */
  public function exceptionErrorHandler(int $p_errNo, string $p_errStr, string $p_errFile, string $p_errLine) {
     throw new ErrorException($p_errStr, 0, $p_errNo, $p_errFile, $p_errLine);
  }

  public function logOccuredError($p_errorMesg ='Unknown error', $p_errFile, $p_errLine) : void {
     // Log occured error
     $customDateTimeObj = CustomDateTime::getInstance();
     $logEntry = sprintf('%s, Error: %s, File: %s:%d'. PHP_EOL, $customDateTimeObj->getFormatedDatetime(), $p_errorMesg, $p_errFile, $p_errLine);
     // Write to log-file.
     $fileHandler = FileHandler::getInstance();
     $fileHandler->appendToFile($this->getAttr_siteLogfile(), $logEntry);
  }
 
  /**
   * Wrapper to handle uncaught exceptions
   * @param Throwable $p_exception
   */
  public function handleException(Throwable $p_exception) : void {
     // Log occcured error.
     $this->logOccuredError($p_exception->getMessage(), $p_exception->getFile(), $p_exception->getLine());
	  // Make sure we still output fatal error headers, catching exception otherwise it will default to status-code 200.
	  $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1');
	  header($protocol .' 500 Internal Server Error');
     $arrLastError = error_get_last();
     $this->displayInternalError($arrLastError);
     exit(1);
  }

  public function shutdownHandler() : void {
     if ($arrLastError = error_get_last()) {
       switch($arrLastError['type']) {
          case E_CORE_ERROR:
          case E_CORE_WARNING:
          case E_COMPILE_ERROR:
          case E_PARSE:
          case E_RECOVERABLE_ERROR:
          case E_ERROR:
          case E_USER_ERROR: {
             // Fatal error occurred.
             $this->displayInternalError($arrLastError);
           break;
          }
       } // case
     }
  }

  protected function displayInternalError($p_arrLastError) : void {
     $errorController = ErrorController::getInstance(APP_LANGUAGE_IDENT, 'utf8', $this->getAppInstance());
     $errorController->displayErrorPage500($p_arrLastError);
  }

  protected function displayLastError($p_arrLastError) : void {
     $errorController = ErrorController::getInstance(APP_LANGUAGE_IDENT, 'utf8', $this->getAppInstance());
     $errorController->renderError($p_arrLastError);
  }

  public function displayErrorMessage(string $p_errorMessage ='') : void {
     $errorController = ErrorController::getInstance(APP_LANGUAGE_IDENT, 'utf8', $this->getAppInstance());
     $errorController->renderErrorMessage($p_errorMessage);
  }
} // End class