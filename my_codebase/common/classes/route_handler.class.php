<?php
namespace Common\Classes;

use Exception;
use ReflectionMethod;
use Common\Classes\Url;
use Common\Classes\ResponseCode;
use Common\Classes\JsonConfigReader;
use Common\Classes\CustomString;
use Common\Classes\StdApp;
use Common\Classes\Controller\ErrorController;

/**
 * Filename     : route_handler.class.php
 * Language     : PHP v7.4+
 * Version      : @version 1.5
 * Date created : 2023-07-19, Ivan
 * Last modified: 2025-01-24, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * DESCRIPTION:
 * This class is my advanced generic route-handler that uses a route-based system-setup.
 *
 * The primary goal of the route-handler is to handle and route every request to our server
 * in an object-oriented and a clever uniform manner and at the same time make the
 * HTTP-response fit the state of every in-comming HTTP-request.
 * This way the HTTP-response will also be dynamic and based on both the configured web-pages and APIs.
 *
 * Any route-handler should be able to handle all request in a uniform way.
 * Also the faulty requests which do not go any where.
 *
 * An important job of the route-handler is also to delegate control to the right controller-class 
 * and it is doing so by calling the configured method in that controller-class.
 * Which in turn will handle the dynamic server-request.
 *
 * But only if there is a match on both the used URI-pattern and the used HTTP-method in the current server-request.
 * That way the starter-scripts can also have generic code for bootstraping the web-application
 * no matter what the server-request.
 * 
 * This way of doing things also follows best-practices in terms of security for the web-application.
 * Cause the end-user will ONLY be able to access what you configure and how you configure it.
 * 
 * @example 1:
 * $routesPath ='/var/www/mywebapp/routes/';  
 * $routeHandler = RouteHandler::getInstance($routesPath);
 * $routeHandler->dispatch();
 * 
 * @example 2: An example of routes entry in web-routes.json
 * "/da/": {
 *  "route_allowed_methods": "HEAD,GET",
 *  "route_controller_class": "FrontPageController",
 *  "route_controller_method": "renderFrontPage",
 *  "route_class_namespace": "App\\Modules\\Frontpage\\Classes\\Controller"
 * }
 */
// Exception related to this class.
class RoutesFileNotExistException extends Exception {}

class RouteHandler
{
  const MAX_URI_LENGTH =512;

  /**
   * Where to look for configured routes.
   * @var string
   */
  protected $pathRoutes;

  /**
   * Known routes
   * @var array
   */
  protected $arrRoutes;

  /**
   * @param string $p_pathRoutes Default blank.
   */
  public function __construct(string $p_pathRoutes ='') {
    $this->setRoutesPath($p_pathRoutes);
    $this->arrRoutes =[];
  }

  public function __destruct() {
  }

  /**
   * @param string $p_pathRoutes
   * @return RouteHandler
   */
  public static function getInstance(string $p_pathRoutes ='') : RouteHandler {
    return new RouteHandler($p_pathRoutes);
  }

  /**
   * @param string $p_pathRoutes
   */
  protected function setRoutesPath(string $p_pathRoutes) : void {
    $this->pathRoutes = $p_pathRoutes;
  }

  /**
   * @return string
   */
  public function getRoutesPath() : string {
    return $this->pathRoutes;
  }

  /**
   * @return void 
   */
  protected function setKnownRoutes(array $p_arrRoutes =[]) : void {
    $this->arrRoutes = clone $p_arrRoutes;
  }

  /**
   * @return array
   */
  protected function getKnownRoutes() : array {
    return $this->arrRoutes;
  }

  /**
   * Loads the JSON configuration-file with pre-configured legal-requests.
   * I have defined one config-file for web-related and another for API-related requests.
   * 
   * @param string $p_filename
   * @return void
   * @throws Exception
   * @throws RoutesFileNotExistException
   */
  protected function loadRoutesFile(string $p_filename) : void {
    $pathRoutes = $this->getRoutesPath();
    try {
      $routesFile = $pathRoutes . $p_filename;
      if (!JsonConfigReader::doesFileExists($routesFile)) {
        throw new RoutesFileNotExistException(sprintf('The routes-file %s was NOT found on the web-server ...', $routesFile));
      } else {
        // Use the JsonConfigReader to load the JSON-file.
        $jsonConfigReader = JsonConfigReader::getInstance($pathRoutes, $p_filename);
        try {
          // Load the JSON Routes configuration-file.
          $this->arrRoutes = $jsonConfigReader->load();
        } catch(Exception $e) {
          // Re-throw the exception.
          throw new Exception($e->getMessage(), $e->getCode());
        }
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

 /**
  * Checks if there is any match on the known routes for the requested URI-path.
  * @param reference $p_pbr_requestURIPath
  * @return bool
  */
  protected function doesRequest_matchAnyRoutes(&$pbr_requestURIPath ='/') : bool {
    $strObj_requestURIPath = CustomString::getInstance($pbr_requestURIPath, 'UTF-8');
    if ($strObj_requestURIPath->doesOccur('index.php')) {
      // Remove index.php from the URI-path and make it match with URI insted.
      $strObj_requestURIPath->doReplacement('index.php', '');
      $pbr_requestURIPath = $strObj_requestURIPath->getAttr_str();
      return array_key_exists($pbr_requestURIPath, $this->arrRoutes['routes']);
    } else {
      return array_key_exists($pbr_requestURIPath, $this->arrRoutes['routes']);
    }
  }

  /**
   * Checks if the current request-method is one of the allowed methods.
   * @param string $p_allowedMethods If more methods it shold be a comma-separated list of methods
   * @param string $p_requestMethod Current request-method.
   * @return bool
   */
  protected function doesRequest_matchAllowedMethods(string $p_allowedMethods, string $p_requestMethod) : bool {
    $strObj_methods = CustomString::getInstance($p_allowedMethods, 'UTF-8');
    return $strObj_methods->doesOccur($p_requestMethod);
  }

  /**
   * This method delegates the control to the program.
   * @param string $p_controllerClass
   * @param string $p_controllerMethod
   * @param string $p_namespace
   * @throws Exception
   * @return void
   */
  protected function delegateControl(string $p_controllerClass, string $p_controllerMethod, $p_namespace =__NAMESPACE__, StdApp $p_appInstance) : void {
    try {
      // Dynamically instanciate the configured controller-class.
      $controllerInstance = call_user_func_array(sprintf("%s::getInstance", $p_namespace .'\\'. $p_controllerClass), array($p_appInstance->getLanguageIdent(), APP_DEFAULT_CHARSET, $p_appInstance));
      if (!is_callable($controllerInstance) && !is_object($controllerInstance)) {
        throw new Exception(sprintf("Controller class %s is NOT callable in the current scope or dont exists ...", $p_controllerClass));
        exit(1);
      } else {
        // Check if the controller-method exists 
        if ($controllerInstance->hasNamedMethod($p_controllerMethod)) {
          $methodReflection = new ReflectionMethod($controllerInstance, $p_controllerMethod);
          // Check if requested method is public.
          if ($methodReflection->isPublic()) {
            // Delegate control to the controller-class.
            $controllerInstance->$p_controllerMethod();
          } else {
            @header('Location: error404');
          }
        } else {
          @header('Location: error404');
        }
      }
    } catch (Exception $e) {
      // Re-throw the Exception
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

  /**
   * This method handles any HTTP-request to the web-server.
   * @param StdApp $p_appInstance
   * @return void
   */
  public function dispatch(StdApp $p_appInstance) : void {
    $url = Url::getInstance();
    $requestURI = $url->getRequestURI();
    if (!isset($requestURI)) {
      // Refruse to handle any buggy HTTP-request
      $this->respondWithHTTPCode(ResponseCode::HTTP_BAD_REQUEST);
      exit(1);
    } else {
      // Check if the URI is grater than our maximum allowed URI-length.
      if ($url->getLength_requestedURI($requestURI) > self::MAX_URI_LENGTH) {
        // Refruse to handle the HTTP-request
        $this->respondWithHTTPCode(ResponseCode::HTTP_URI_TOO_LONG);
        exit(1);
      } else {
        // Try process the HTTP-request.
        $requestURIPath = $url->getURLElement_path($requestURI);

        // Set requested language-ident.
        $requestLanguageIdent = $url->getFirstParameter_ofPath($requestURIPath);
        if (!empty($requestLanguageIdent)) {
          // Check if requested language-ident is supported
          if (!$p_appInstance->isSupported_requestedLanguageIdent($requestLanguageIdent)) {
            // If language-ident is not supported - use default for the rendering.
            $p_appInstance->setLanguageIdent();
          } else {
            $p_appInstance->setLanguageIdent($requestLanguageIdent);
          }
        }

        // Load all the pre-defined routes for the current routes-type.
        $isAPIRequest = $url->isAPIRequest_path($requestURIPath);
        if ($isAPIRequest) {
          $this->loadRoutesFile('api-routes.json');
        } else {
          $this->loadRoutesFile('web-routes.json');
        }

        // Check if there is a match in the patterns of the request-URI within the existing routes.
        if (!$this->doesRequest_matchAnyRoutes($requestURIPath)) {
          // There was no match using URI-path.
          if ($isAPIRequest) {
            // Bad request!
            $this->respondWithHTTPCode(ResponseCode::HTTP_BAD_REQUEST);
          } else {
            // Display a 404-page
            $errorController = ErrorController::getInstance($p_appInstance->getLanguageIdent(), APP_DEFAULT_CHARSET, $p_appInstance);
            $errorController->displayError_page404();
          }
          exit(0);
        } else {
          // Yes, there was a match on route-path.
          $arrRouteMatch = $this->arrRoutes['routes'][$requestURIPath];
          // Check if the request-method also match the allowed request-methods for the matched routes-entry.
          if (!$this->doesRequest_matchAllowedMethods($arrRouteMatch['route_allowed_methods'], $url->getRequestMethod())) {
            /* Send Allow HTTP-header and list the set of methods supported by a resource.
             * This header must be sent, if the server responds with a 405 Method - Not Allowed status code to indicate which request methods can be used.
             * An empty Allow header indicates that the resource allows no request methods, which might occur temporarily.
             */
            header(sprintf('Allow: %s', $arrRouteMatch['route_allowed_methods']), TRUE, ResponseCode::HTTP_METHOD_NOT_ALLOWED);
            exit(0);
          } else {
            // Check if the controller-class exists within the namespace.
            $requiredControllerClass = $arrRouteMatch['route_class_namespace'] .'\\'. $arrRouteMatch['route_controller_class'];
            if (!class_exists($requiredControllerClass)) {
              if ($isAPIRequest) {
                $this->respondWithHTTPCode(ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
              } else {
                // Display 500 page with error-message.
                $errorController = ErrorController::getInstance($p_appInstance->getLanguageIdent(), APP_DEFAULT_CHARSET, $p_appInstance);
                $errorController->renderErrorMessage(sprintf('Error orccured the constroller-class %s was not found in the given namespace ', $requiredControllerClass));
              }
              exit(1);
            } else {
              try {
                // Request-method match the allowed, so lets delegate control to the controller-class.
                $this->delegateControl($arrRouteMatch['route_controller_class'],
                                       $arrRouteMatch['route_controller_method'],
                                       $arrRouteMatch['route_class_namespace'],
                                       $p_appInstance);
                exit(0);
              } catch (Exception $e) {
                if ($isAPIRequest) {
                  $this->respondWithHTTPCode(ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
                } else {
                  // Display 500 page with error-message.
                  $errorController = ErrorController::getInstance($p_appInstance->getLanguageIdent(), APP_DEFAULT_CHARSET, $p_appInstance);
                  $errorController->renderErrorMessage($e->getMessage());
                }
                exit(1);
              }
            }
          }
        }
      }
    }
  }

  /**
   * @param int $p_responseCode
   * @return void
   */
  public function respondWithHTTPCode(int $p_responseCode =ResponseCode::HTTP_BAD_REQUEST) : void {
    http_response_code($p_responseCode);
  }
}