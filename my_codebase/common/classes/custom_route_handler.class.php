<?php
namespace Common\Classes;

use Exception;
use ReflectionMethod;
use Common\Classes\FileHandler;

/**
 * Filename     : custom_route_handler.class.php
 * Language     : PHP v7+
 * Version      : @version 0.4
 * Date created : 16/02-2016, Ivan
 * Last modified: 06/12-2020, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * Description:
 *  This Router-class parses the request-URI to controller and method.
 *
 *  The primary purpose of the router-class is to delegate the control to the right controller-class and method.
 *  Secondary the router should handle all request in a uniform way also faulty requests which do not go any where.
 *  So the starter-scripts will now look generic and will NOT contain any specific calls to controller-classes.
 *  The specific URI, controller-class and method goes inside a special route config-file.
 *  The controller-class which gets delegated the control, will it self make instances of needed model-classes.
 *
 * @example
 *  $routeHandler = CustomRouteHandler::getInstance();
 *  $routeHandler->handleRequest($this);
 */

// Exception related to this class.
class RoutesFileNotExistsException extends Exception {}

class CustomRouteHandler
{
  /**
   * @var string
   */
  private $requested_uri;

  /**
   * @var string
   */
  private $requested_script;

  private $arrPossibleRequests;

  /**
   * @param string $p_requestURI
   * @param string $p_requestedScript
   */
  public function __construct(string $p_requestedURI ='/', string $p_requestedScript ='') {
    $this->setAttr_requested_uri($p_requestedURI);
    $this->setAttr_requested_script($p_requestedScript);
  }

  public function __destruct() {
  }

  /**
   * @param string $p_requestedURI Default '/'.
   */
  private function setAttr_requested_uri(string $p_requestedURI ='/') : void {
    $this->requested_uri = (string) $p_requestedURI;
  }

  /**
   * @return string
   */
  public function getAttr_requested_uri() : string {
    return $this->requested_uri;
  }

  /**
   * @param string $p_requestedScript
   */
  private function setAttr_requested_script(string $p_requestedScript) : void {
     $this->requested_script = $p_requestedScript;
  }

  /**
   * @return string
   */
  public function getAttr_requested_script() : string {
    return $this->requested_script;
  }

  /**
   * @return CustomRouteHandler
   */
  public static function getInstance() : CustomRouteHandler {
    if (isset($_SERVER['REQUEST_URI'])) {
      $requestURI = $_SERVER['REQUEST_URI'];
    } else {
      $requestURI = '';
    }
    return new CustomRouteHandler($requestURI, $_SERVER['SCRIPT_NAME']);
  }

  /**
   * @throws RoutesFileNotExistsException
   * @return void
   */
  private function loadPossibleRequests() : void {
     $configFilename = PATH_SITE_CONFIG . SITE_ROUTE_FILE;
     if (!FileHandler::doesFileExists($configFilename)) {
//       trigger_error(sprintf('The routes-file of the site do NOT exists in directory: %s ', PATH_SITE_CONFIG), E_USER_ERROR);
       throw new RoutesFileNotExistsException(sprintf('The routes-file of the site do NOT exists in directory: %s ', PATH_SITE_CONFIG));
       exit(1);
     } else {
       try {
         require_once($configFilename);
         $this->arrPossibleRequests = $arrPossibleRequests;
       } catch (Exception $e) {
         echo $e->getMessage();
       }
     }
  }

  public function handleRequest($p_appInstance) : void {
     try {
       $this->loadPossibleRequests();
       $requestedScript = $this->getAttr_requested_script();
       if (array_key_exists($requestedScript, $this->arrPossibleRequests)) {
         $arrRouteInfo = $this->arrPossibleRequests[$requestedScript];
         if (file_exists($arrRouteInfo['controller_file'])) {
           $this->prepairDelegation($p_appInstance, $arrRouteInfo['controller_file'], $arrRouteInfo['controller_class'], $arrRouteInfo['controller_method']);
         } else {
           trigger_error(sprintf('The specified controller-file (%s) did not exists on the server ...', $arrRouteInfo['controller_file']), E_USER_ERROR);
         }
       }
     } catch (RoutesFileNotExistsException $e) {
       echo $e->getMessage();
       exit(1);
     }
  }

  /**
   * @param StdApp $p_appInstance
   * @param string $p_controllerClassFilename
   * @param string $p_controllerClassName
   * @param string $p_controllerMethod
   */
  private function prepairDelegation($p_appInstance, $p_controllerClassFilename, $p_controllerClassName, $p_controllerMethod) : void {
     $controllerObj = $this->getControllerInstance($p_controllerClassFilename, $p_controllerClassName);
     if (is_object($controllerObj) && ($controllerObj instanceof $p_controllerClassName)) {
       // Set the app-instance using StdController method
       $controllerObj->setAppInstance($p_appInstance);

       if (method_exists($controllerObj, $p_controllerMethod)) {
         $methodReflectionObj = new ReflectionMethod($controllerObj, $p_controllerMethod);
         // Check if requested method is public.
         if ($methodReflectionObj->isPublic()) {
           $controllerObj->$p_controllerMethod();
         } else {
           @header('Location: error404');
         }
       } else {
         @header('Location: error404');
       }
     }
  }

  /**
   * Checks if controller exists.
   *
   * @param string $p_controllerFilename Controller-filename. Used to include the controller.
   * @return bool
   */
  private function checkControllerExists(string $p_controllerFilename) : bool {
     return file_exists($p_controllerFilename);
  }

  /**
   * @param string $p_controllerFilename
   * @param string $p_controllerClassName
   * @return mixed
   * @throws Exception
   */
  private function getControllerInstance($p_controllerFilename, $p_controllerClassName) {
     try {
       if ($this->checkControllerExists($p_controllerFilename)) {
         require_once($p_controllerFilename);
         return $p_controllerClassName::getInstance(APP_LANGUAGE_IDENT, APP_DEFAULT_CHARSET);
       } else {
         throw new Exception(sprintf('The requested controller-file %s was NOT found ...', $p_controllerFilename));
         exit(2);
       }
     } catch (Exception $e) {
       trigger_error($e->getMessage(), E_USER_ERROR);
       exit(1);
     }
  }
} // End class