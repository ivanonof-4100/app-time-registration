<?php
namespace Common\Classes;
use Common\Classes\CustomString;

/**
 * Filename     : url.class.php
 * Language     : PHP v7+
 * Version      : @version 1.1
 * Date created : 2023-07-19, Ivan
 * Last modified: 2024-07-22, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * DESCRIPTION:
 * This class encapulates operations accessing the data from the URL and URI.
 *
 * @example 1:
 * $url = Url::getInstance();
 * if ($url->isRequestMethod_HEAD() || $url->isRequestMethod_GET()) {
 *   $requestURI = $url->getRequestURI();
 * }
 */
class Url
{
    public function __construct() {
    }

    public function __destruct() {  
    }

    public static function getInstance() : Url {
      return new Url();
    }

    // Service methods

    /**
     * @return string
     */
    public function getRequestURI() : string {
      if (php_sapi_name() == 'cli') {
        return '';
      } else {
        return $_SERVER['REQUEST_URI'];
      }
    }

    /**
     * Returns the request-method of the current request.
     * @return string
     */
    public static function getRequestMethod() : string {
      return $_SERVER['REQUEST_METHOD'];
    }

    public function isRequestMethod_GET() : bool {
      return (self::getRequestMethod() === 'GET');
    }

    public function isRequestMethod_HEAD() : bool {
      return (self::getRequestMethod() === 'HEAD');
    }

    public function isRequestMethod_POST() : bool {
      return (self::getRequestMethod() === 'POST');
    }

    public function isRequestMethod_PUT() : bool {
      return (self::getRequestMethod() === 'PUT');
    }

    public function isRequestMethod_PATCH() : bool {
      return (self::getRequestMethod() === 'PATCH');
    }

    public function isRequestMethod_DELETE() : bool {
      return (self::getRequestMethod() === 'DELETE');
    }

    /**
     * @param string $p_requestURI Default blank.
     * @return array
     */
    public function getURLElements(string $p_requestURI ='') : array {
      return parse_url($p_requestURI);
    }

    /**
     * @return string
     */
    public function getURLElement_protocol(string $p_requestURI) : string {
      return $this->urlDecodeValue(parse_url($p_requestURI, PHP_URL_SCHEME));
    }

    /**
     * @return string
     */
    public function getURLElement_host(string $p_requestURI) : string {
      return $this->urlDecodeValue(parse_url($p_requestURI, PHP_URL_HOST));
    }

    /**
     * @return string
     */
    public function getURLElement_port(string $p_requestURI) : string {
      return $this->urlDecodeValue(parse_url($p_requestURI, PHP_URL_PORT));
    }

    /**
     * @return string
     */
    public function getURLElement_user(string $p_requestURI) : string {
      return $this->urlDecodeValue(parse_url($p_requestURI, PHP_URL_USER));
    }

    /**
     * @return string
     */
    public function getURLElement_pass(string $p_requestURI) : string {
      return $this->urlDecodeValue(parse_url($p_requestURI, PHP_URL_PASS));
    }

    /**
     * @param string $p_requestURI
     * @return string
     */
    public function getURLElement_path(string $p_requestURI) : string {
      return $this->urlDecodeValue(parse_url($p_requestURI, PHP_URL_PATH));
    }

    /**
     * @return string
     */
    public function getFirstParameter_ofPath(string $p_strPath ='') : string {
      $strPath = CustomString::getInstance($p_strPath, CustomString::UTF8_ENCODING);
      if ($strPath->isBlank()) {
        return '';
      } else {
        $foundPos_afterFirstPathValue = $strPath->getPosition_firstOccurrence('/', 1);
        return $strPath->getSubString($p_strPath, 1, $foundPos_afterFirstPathValue-1);
      }
    }

    /**
     * Checks if the URI-path starts with /api/ then it must be an API-call.
     * @return bool
     */
    public function isAPIRequest_path(string $p_requestURIPath ='') : bool {
      $strURIPath = CustomString::getInstance($p_requestURIPath);
      if ($strURIPath->doesOccur('/api/')) {
        return TRUE;
      } else {
        return FALSE;
      }
    }

    /**
     * @return string
     */
    public function getURLElement_query(string $p_requestURI) : string {
      return $this->urlDecodeValue(parse_url($p_requestURI, PHP_URL_QUERY));
    }

    /**
     * @param $p_encodedValue
     * @return string
     */
    protected function urlDecodeValue($p_encodedValue) : string {
      return urldecode($p_encodedValue);
    }

    /**
     * @param string $p_requestURI Default blank.
     * @return int
     */
    public function getLength_requestedURI(string $p_requestURI ='') : int {
      $strObj = CustomString::getInstance($p_requestURI);
      return $strObj->getStringLengthOfInstance();
    }
}