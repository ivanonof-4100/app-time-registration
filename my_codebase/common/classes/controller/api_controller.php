<?php
namespace Common\Classes\Controller;

use Exception;
use Common\Classes\StdApp;
use Common\Classes\ResponseCode;
use Common\Classes\Helper\MimeType;
use Common\Classes\OutputBuffer;
use Common\Classes\Controller\StdController;

/**
 * Filename     : api_controller.class.php
 * Language     : PHP v7.4+
 * Date created : Ivan, 07/12-2022
 * Last modified: Ivan, 24/01-2025
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright: Copyright (C) 2025 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * This class is my standard implementation of API-request handling using the correct HTTP status-codes.
 * The idear here is that you can easy write your API by inheriting this standard implementation
 * and just re-implement the CRUD-methods that you actually use.
 *
 * @see: https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
 * @see: https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
 * @see: https://developer.mozilla.org/en-US/docs/Web/HTTP/Authentication
 * The OAuth 2.0 Authorization Framework: Bearer Token Usage
 * @see: https://datatracker.ietf.org/doc/html/rfc6750
 */
class ApiController extends StdController {
    /**
     * Constructor
     */
    public function __construct(StdApp $p_appInstance) {
        parent::__construct($p_appInstance);
    }

    public function __destruct() {
        parent::__destruct();        
    }

    /**
     * Initialize dependencies and the registry of the web-app for APIs.
     * @return void
     */
    public function initalizeDependencies() : void {
        $arrSettings = $this->getLoadedSettings();
        try {
          // Connect to database and start session-handling.
          $this->initDependencies($arrSettings, TRUE);
        } catch (Exception $e) {
          $this->sendHttpResponse(ResponseCode::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
          exit(0);
        }
    }

    /**
     * Send raw HTTP-header before displaying the web-page.
     * @param int $p_useResponceCode Default 200
     * @param string $p_mimeType
     * @param string $p_contentCharset Default 'UTF-8'
     * @return void
    */
    protected function sendResponseHeaders(int $p_useResponceCode =ResponseCode::HTTP_OK, string $p_mimeType, string $p_contentCharset ='UTF-8') : void {
        if (OutputBuffer::doesBrowserSupport_compressedContent()) {
          header(sprintf('Content-Type:%s; charset=%s; Content-Encoding:gzip;', $p_mimeType, $p_contentCharset), TRUE, $p_useResponceCode);
        } else {
          header(sprintf('Content-Type:%s; charset=%s', $p_mimeType, $p_contentCharset), TRUE, $p_useResponceCode);
        }
    }

/*
    public function handleUserAuthentication) {
        return true;
    }
*/

    // Handle API CRUD-operations.

    /**
     * CRUD Create-operation.
     */
    public function handleRequest_create() {
        $this->handleRequest_sendNotImplemented();
    }

    /**
     * CRUD Read-operation.
     */
    public function handleRequest_read() {
        $this->handleRequest_sendNotImplemented();
    }

    /**
     * CRUD Update-operation.
     */
    public function handleRequest_update() {
        $this->handleRequest_sendNotImplemented();
    }

    /**
     * CRUD Delete-operation.
     */
    public function handleRequest_delete() {
        $this->handleRequest_sendNotImplemented();
    }

    /**
     * @param int $p_httpStatusCode Default 200
     * @param string $p_responseContent
     * @return void
     */
    public function sendHttpResponse(int $p_httpStatusCode =ResponseCode::HTTP_OK, string $p_responsContent ='') : void {
        http_response_code($p_httpStatusCode);
        echo $p_responsContent;
    }

    /**
     * @param int $p_httpStatusCode Default 200
     * @param $p_responseContent
     * @return void
     */
    public function sendJSONResponse(int $p_httpStatusCode =ResponseCode::HTTP_OK, $p_responseContent) : void {
        if (is_object($p_responseContent) || is_array($p_responseContent)) {
            $this->sendResponseHeaders($p_httpStatusCode, MimeType::getMimeType_forObject(MimeType::OBJ_TYPE_DATA, MimeType::DATA_VARIANT_JSON));
            echo json_encode($p_responseContent, JSON_OBJECT_AS_ARRAY);
            http_response_code($p_httpStatusCode);
        } else {
            http_response_code(ResponseCode::HTTP_NOT_IMPLEMENTED);
        }
    }

    /**
     * If the requested entity to save has one or more invalid attributes then
     * Send HTTP status-code 401 Unauthorized
     * @param string $p_explainerText
     * @return void
     */
    public function handleRequest_sendUnauthorized(string $p_explainerText ='Unauthorized') : void {
        $this->sendHttpResponse(ResponseCode::HTTP_UNAUTHORIZED, $p_explainerText);
    }

    /**
     * Sends HTTP status-code 403: Forbidden.
     *
     * If the user that requested the action does not have the permissions to do the operation its forbidden.
     * Then send HTTP status-code 403 Forbidden
     * @param string $p_explainerText
     * @return void
     */
    public function handleRequest_sendForbidden(string $p_explainerText ='Forbidden') : void {
        $this->sendHttpResponse(ResponseCode::HTTP_FORBIDDEN, $p_explainerText);
    }

    /**
     * Sends HTTP status-code 405: Method Not Allowed.
     *
     * The request method is known by the server but is not supported by the target resource.
     * For example, an API may not allow calling DELETE to remove a resource.
     * 
     * @param string $p_explainerText
     * @return void
     */
    public function handleRequest_sendMethodNotAllowed(string $p_explainerText ='Method Not Allowed') : void {
        $this->sendHttpResponse(ResponseCode::HTTP_METHOD_NOT_ALLOWED, $p_explainerText);
    }

    /**
     * If the requested entity to save has one or more invalid attributes then
     * Send HTTP status-code 422 Unprocessable entity
     * @param string $p_explainerText
     * @return void
     */
    public function handleRequest_sendUnprocessableEntity(string $p_explainerText ='Unprocessable Entity') : void {
        $this->sendHttpResponse(ResponseCode::HTTP_UNPROCESSABLE_CONTENT, $p_explainerText);
    }

    /**
     * If the requested item-id to update in the database does NOT exists then
     * Send HTTP status-code 410 Gone
     * @param string $p_explainerText
     * @return void
     */
    public function handleRequest_sendEntityGone(string $p_explainerText ='Gone') : void {
        $this->sendHttpResponse(ResponseCode::HTTP_GONE, $p_explainerText);
    }

    /**
     * If the requested entity was created successfully then
     * Send HTTP status-code 201 Created together with the JSON-string of the newly created instance. 
     * @param string $p_explainerText
     * @return void
     */
    public function handleRequest_sendEntityCreated(string $p_explainerText ='Created') : void {
        $this->sendHttpResponse(ResponseCode::HTTP_CREATED, $p_explainerText);
    }

    /**
     * If the unique entity-id to create allready exists then
     * Send HTTP status-code 409: Conflict. 
     * @param string $p_explainerText
     * @return void
     */
    public function handleRequest_sendEntityConflict(string $p_explainerText ='Conflict') : void {
        $this->sendHttpResponse(ResponseCode::HTTP_CONFLICT, $p_explainerText);
    }

    /**
     * Sends HTTP status-code 501: Not Implemented
     * 
     * The request method is not supported by the server and cannot be handled.
     * The only methods that servers are required to support
     * (and therefore that must not return this code) for GET and HEAD.
     */
    public function handleRequest_sendNotImplemented(string $p_explainerText ='Not Implemented') : void {
        $this->sendHttpResponse(ResponseCode::HTTP_NOT_IMPLEMENTED, $p_explainerText);
    }

    /**
     * Sends HTTP status-code 503: Service Unavailable. 
     * 
     * The server is not ready to handle the request.
     * Common causes are a server that is down for maintenance or is overloaded.
     *
     * @param string $p_explainerText
     * @return void
     */
    public function handleRequest_sendServiceUnavailable(string $p_explainerText ='Service Unavailable') : void {
        $this->sendHttpResponse(ResponseCode::HTTP_SERVICE_UNAVAILABLE, $p_explainerText);
    }
}