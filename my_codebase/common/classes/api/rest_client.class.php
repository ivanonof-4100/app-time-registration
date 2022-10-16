<?php
/**
 * Filename     : rest_client.class.php
 * Language     : PHP v5.x
 * Date created : 04/07-2009, Diogo
 * Last modified: 27/10-2012, Ivan
 * Developers   : @author Diogo Souza da Silva <manifesto@manifesto.blog.br>
 *                @author Ivan Mark Andersen  <ivanonof@gmail.com>
 * Description:
 *  This class wraps HTTP calls using cURL, aimed for accessing and testing RESTful web-service.
 *  REpresentational State Transfer (REST) is a style of software architecture for distributed systems
 *  such as the World Wide Web. REST has emerged as a predominant Web service design model.
 * 
 *  This class can be used to send web-service requests to REST-Service APIs.
 *  It can send GET, POST, PUT and DELETE HTTP-requests to a Web-service server that supports the REST-protocol.
 * 
 *  The class also supports HTTP basic-authentication, passing parameters in the requested URL,
 *  send HTTP POST-requests using either arrays with form-data values or raw POST-data, and use a custom content-type header value.
 * 
 *  Note!
 *  Please note that curl is a PHP-extension and if you dont have curl installed?
 *  Here is how-to install it on a Debian-deverted Linux-distros like Ubuntu GNU/Linux
 *  
 *  The full install command:
 *   sudo apt-get install curl libcurl4 php7.2-curl
 *  
 *  Restart your Apache- or NginX web-server.
 *  You'll know if it works because phpinfo() will get a new section with Curl info.
 */

// Exception related to this class.
class RESTClientErrorException extends Exception {}

class RestClient
{
     protected $curlObj;
     protected $url;
     protected $response ='';
     protected $headers = array();
     protected $method ='GET';
     protected $params =null;
     protected $contentType = null;
     protected $file =null;

     /**
      * Constructor, sets default options
      * @return RestClient
      */
     public function __construct() {
        // $this->curlObj = curl_init();
        $this->curlObj = self::initializeCurlSession();

        if ($this->curlObj) {
          curl_setopt($this->curlObj, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($this->curlObj, CURLOPT_AUTOREFERER, true); // This make sure will follow redirects.
          curl_setopt($this->curlObj, CURLOPT_FOLLOWLOCATION, true); // This too.
          curl_setopt($this->curlObj, CURLOPT_HEADER, true); // This verbose option for extracting the headers.
          // curl_setopt($this->curlObj, CURLOPT_ERRORBUFFER);
        } else {
          trigger_error(__METHOD__.': Error occurred while initializing the cURL instance ...', E_USER_ERROR);
        }
     } // method __construct

     public function __destruct() {
     } // method __destruct

     public static function initializeCurlSession() {
        try {
          return curl_init();
        } catch (Exception $e) {
          trigger_error('It was not possible to initialize a new cURL-session and return a handle ...'. PHP_EOL .$e->getMessage(), E_USER_ERROR);
        }
     } // method initializeCurlSession

     /**
      * Creates the RESTClient
      *
      * @param string $p_url =null [optional]
      * @return RestClient
      */
     public static function getInstance($p_url =null) : RestClient {
        $clientObj = new RestClient();
        if (!is_null($p_url)) {
          $clientObj->setUrl($p_url);
        }

        return $clientObj;
     } // method getInstance

     /**
      * @return 
      */
     protected function getInstance_cURL() {
        return $this->curlObj;
     } // method getInstance_cURL

     /**
      * @return boolean
     */
     protected function isRequestMethodGET() : boolean {
        return ($this->getRequestMethod() === 'GET');
     } // method isRequestMethodGET

     /**
      * @return boolean
     */
     protected function isRequestMethodPOST() : boolean {
        return ($this->getRequestMethod() === 'POST');
     } // method isRequestMethodPOST

     /**
      * @return boolean
     */
     protected function isRequestMethodPUT() : boolean {
        return ($this->getRequestMethod() === 'PUT');
     } // method isRequestMethodPUT

     /**
      * Execute the call to the web-service.,
      * @throws RESTClientErrorException on errors.
     */ 
     public function execute() : void {
        $cURLObj = $this->getInstance_cURL();

        if ($this->isRequestMethodGET()) {
          curl_setopt($cURLObj, CURLOPT_HTTPGET, true);
          $this->treatURL();
        } else if ($this->isRequestMethodPOST()) {
          curl_setopt($cURLObj, CURLOPT_POST, true);
          curl_setopt($cURLObj, CURLOPT_POSTFIELDS, $this->params);
        } else if($this->isRequestMethodPUT()) {
          curl_setopt($cURLObj, CURLOPT_PUT, true);
          $this->treatURL();
          $this->file = tmpFile(); // unaccessable/implemented method
          fwrite($this->file,$this->params);
          fseek($this->file,0);
          curl_setopt($cURLObj, CURLOPT_INFILE, $this->file);
          curl_setopt($cURLObj, CURLOPT_INFILESIZE, strlen($this->params));
        } else {
          // Custom request-method.
          curl_setopt($cURLObj, CURLOPT_CUSTOMREQUEST, $this->getRequestMethod());
        }

        curl_setopt($cURLObj, CURLOPT_URL, $this->getUrl());

        if ($this->contentType != null) {
          curl_setopt($cURLObj, CURLOPT_HTTPHEADER, array("Content-Type: ".$this->contentType));
        }

        try {
          $cURLResponse = curl_exec($cURLObj);
          // Extract the headers, respons-code and response.
          $this->treatResponse($cURLResponse);
        } catch (ErrorException $e) {
          $errorMesg = 'Error ocurred when trying to excute a HTTP-request using cURL (cURL error: '. curl_error($cURLObj) .')'.PHP_EOL. $e->getMessage();
          $this->reportConnectError($errorMesg);

          // Then throw the new exception.
          throw new RESTClientErrorException($errorMesg, $e->getCode(), $e);
        }
     } // method execute

     /**
      * Treats URL
      */
     private function treatURL() {
        if (is_array($this->params) && count($this->params) >= 1) {
          // Transform parameters in key/value pars in URL
          if (!strpos($this->url, '?')) {
            $this->url .= '?';
            foreach ($this->params as $k=>$v) {
              $this->url .= '&'.urlencode($k).'='.urlencode($v);
            }
          }
        }

        return $this->url;
     } // method treatURL

     /**
      * Treats the Response for extracting the Headers and Response
     */ 
     private function treatResponse($r) {
        if ($r == null || strlen($r) < 1) {
          return;
        }

        $parts = explode("\n\r", $r); // HTTP packets define that Headers end in a blank line (\n\r) where starts the body
        while(preg_match('@HTTP/1.[0-1] 100 Continue@',$parts[0]) || preg_match("@Moved@",$parts[0])) {
            // Continue header must be bypass
            for($i=1;$i<count($parts);$i++) {
                $parts[$i -1] = trim($parts[$i]);
            }
            unset($parts[count($parts) -1]);
        }
        preg_match("@Content-Type: ([a-zA-Z0-9-]+/?[a-zA-Z0-9-]*)@",$parts[0],$reg);// This extract the content type
        $this->headers['content-type'] = $reg[1];
        preg_match("@HTTP/1.[0-1] ([0-9]{3}) ([a-zA-Z ]+)@",$parts[0],$reg); // This extracts the response header Code and Message
        $this->headers['code'] = $reg[1];
        $this->headers['message'] = $reg[2];
        $this->response = '';
        for ($i=1; $i<count($parts); $i++)
        {
            // This make sure that exploded response get back togheter
            if ($i > 1) {
              $this->response .= "\n\r";
            }
            $this->response .= $parts[$i];
        }
     } // method treatResponse

     /**
      * @return array
      */
     public function getHeaders() {
        return $this->headers;
     }

     /**
      * @return string
      */ 
     public function getResponse() {
        return $this->response;
     } // method getResponse

     /**
      * HTTP response-code (404, 401, 200, etc)
      * @return int|boolean Returns the respons-code in the header if its availble otherwise boolean FALSE.
      */
     public function getResponseCode() {
        $arrHeaders = $this->getHeaders();
        if (is_array($arrHeaders)) {
          if (array_key_exists('code', $arrHeaders)) {
            return (int) $arrHeaders['code']; 
          } else {
            trigger_error(__METHOD__ .': There was not found any code-entry in the HTTP response-headers ...', E_USER_WARNING);
            return FALSE;
          }
        } else {
          trigger_error(__METHOD__ .': Headers was not an array ...', E_USER_WARNING);
          return FALSE;
        }
     } // method getResponseCode

     /**
      * HTTP response-message (Not Found, Continue, etc )
      * @return string
      */
     public function getResponseMessage() : string {
        $arrHeaders = $this->getHeaders();
        if (is_array($arrHeaders)) {
          if (array_key_exists('message', $arrHeaders)) {
            return $arrHeaders['message']; 
          } else {
            trigger_error(__METHOD__ .': There was no message in the HTTP response-header ...', E_USER_WARNING);
            return '';
          }
        } else {
          trigger_error(__METHOD__ .': Headers was not an array ...', E_USER_WARNING);
          return '';
        }
     } // method getResponseMessage

     /**
      * Content-Type ('text/plain', 'application/xml', etc)
      * @return string
      */
     public function getResponseContentType() : string {
        $arrHeaders = $this->getHeaders();
        if (is_array($arrHeaders)) {
          if (array_key_exists('content_type', $arrHeaders)) {
            return $arrHeaders['content_type']; 
          } else {
            trigger_error(__METHOD__ .': No content-type was found in the response-header ...', E_USER_WARNING);
            return '';
          }
        } else {
          trigger_error(__METHOD__ .': Headers was not an array ...', E_USER_WARNING);
          return '';
        }
     } // method getResponseContentType

     /**
      * This sets that will not follow redirects
      */
     public function setNoFollow() {
        $cURLObj = $this->getInstance_cURL();

        curl_setopt($cURLObj, CURLOPT_AUTOREFERER, false);
        curl_setopt($cURLObj, CURLOPT_FOLLOWLOCATION, false);
     } // method setNoFollow

     /**
      * This closes the connection and release resources.
      */
     public function close() {
        $cURLObj = $this->getInstance_cURL();
        curl_close($cURLObj);
        $cURLObj = null;

        if ($this->file != null) {
          fclose($this->file);
        }
     } // method close

     /**
      * Sets the URL to be Called
      * @parm string $p_url
      */
     public function setUrl($p_url) {
        $this->url = (string) $p_url;
     } // method setUrl

     /**
      * @return string
      */
     public function getUrl() {
        return $this->url;
     } // method getUrl

     /**
      * Set the Content-Type of the request to be send
      * Format like "application/xml" or "text/plain" or other
      * 
      * @param string $p_contentType
      */
     public function setContentType($p_contentType) {
        $this->contentType = $p_contentType;
     } // method setContentType

     /**
      * Set the Credentials for BASIC Authentication
      * 
      * @param string $user
      * @param string $pass
      */
     public function setCredentials($user, $pass) {
        if ($user != null) {
          $cURLObj = $this->getInstance_cURL();
          curl_setopt($cURLObj, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
          curl_setopt($cURLObj, CURLOPT_USERPWD,"{$user}:{$pass}");
        }
     }

     /**
      * Set the Request HTTP Method
      * For now, only accepts GET and POST
      * 
      * @param string $p_method Default 'GET'
      */
     public function setRequestMethod($p_method ='GET') {
        $this->method = (string) $p_method;
     } // method setRequestMethod

     /**
      * @return string
      */
     public function getRequestMethod() {
        return $this->method;
     } // method getRequestMethod

     /**
      * Set Parameters to be send on the request
      * It can be both a key/value par array (as in array("key"=>"value"))
      * or a string containing the body of the request, like a XML, JSON or other
      * Proper content-type should be set for the body if not a array
      * 
      * @param mixed $params
      */
     public function setParameters($params) {
        $this->params = (string) $params;
     }

     /**
      * Convenience method wrapping a common GET call.
      *
      * @param string $p_url
      * @param array params
      * @param string $user=null [optional]
      * @param string $password=null [optional]
      * 
      * @return int|boolean
      */
     public function doGETRequest($p_url, array $params =null, $user =null, $pwd =null) {
        return $this->call('GET', $p_url, $params, $user, $pwd);
     } // method doGETRequest

     /**
      * Convenience method wrapping a commom POST call
      * 
      * @param string $url
      * @param mixed params
      * @param string $user=null [optional]
      * @param string $password=null [optional]
      * @param string $contentType="multpary/form-data" [optional] commom post (multipart/form-data) as default.
      * 
      * @return int|boolean
      */
     public function doPOSTRequest($url, $params =null, $user =null, $pwd =null, $contentType ="multipart/form-data") {
        return $this->call('POST', $url, $params, $user, $pwd, $contentType);
     } // method doPOSTRequest

     /**
      * Convenience method wrapping a commom PUT call
      * 
      * @param string $url
      * @param string $body 
      * @param string $user=null [optional]
      * @param string $password=null [optional]
      * @param string $contentType=null [optional]
      * 
      * @return int|boolean
      */
     public function doPUTRequest($url, $body, $user =null, $pwd =null, $contentType =null) {
        return $this->call('PUT', $url, $body, $user, $pwd, $contentType);
     } // method doPUTRequest

     /**
      * Convenience method wrapping a commom delete call
      *
      * @param string $url
      * @param array params
      * @param string $user=null [optional]
      * @param string $password=null [optional]
      *
      * @return int|boolean
      */
     public function delete($url, array $params =null, $user =null, $password =null) {
        return $this->call('DELETE', $url, $params, $user, $password);
     } // method delete

     /**
      * Convenience method wrapping a common custom call.
      *
      * @param string $requestMethod
      * @param string $url
      * @param string $body 
      * @param string $user=null [optional]
      * @param string $password=null [optional]
      * @param string $contentType=null [optional]
      */
     public function call($requestMethod, $url, $body, $user =null, $pwd =null, $contentType =null) {
         $this->setUrl($url);
         $this->setParameters($body);
         $this->setRequestMethod($requestMethod);
         $this->setCredentials($user,$pwd);
         $this->setContentType($contentType);
         try {
           $this->execute();
           $this->close();
           return $this->getResponseCode();
         } catch (RESTClientErrorException $e) {
           $this->close();
           echo PHP_EOL . __METHOD__ .': Our REST-client had trouble doing the HTTP-request ...'. PHP_EOL .$e->getMessage();
           exit(1);
        }
     } // method call
} // End class