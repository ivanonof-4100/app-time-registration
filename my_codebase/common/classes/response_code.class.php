<?php
namespace Common\Classes;

/**
 * Filename     : response_code.class.php
 * Language     : PHP v7.4+
 * Version      : @version 1.1
 * Date created : 2024-04-10, Ivan
 * Last modified: 2024-04-10, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * DESCRIPTION:
 * This class defines all needed HTTP response-codes used in my codebase.
 * This design-approch also allows for easy use else where in my codebase where
 * I want to reference or use a specific HTTP response-code like eg. in the route-handler.
 * It also simplifyes the route-handler when all of this is separated out and makes it all more loosly cuppled.
 * @see:
 * https://developer.mozilla.org/en-US/docs/Web/HTTP/Status
 *
 * @example
 * ResponseCode::HTTP_BAD_REQUEST
 * ResponseCode::HTTP_METHOD_NOT_ALLOWED
 */
class ResponseCode
{
  // Definition of HTTP Response Status-codes
  const HTTP_OK =200;
  // Created: When the resource is uploaded for the first time.
  const HTTP_CREATED =201;
  // Accepted: Used when updating data using APIs
  const HTTP_ACCEPTED =202;
  // No Content: When successful result of a DELETE.
  const HTTP_NO_CONTENT =204;
  const HTTP_RESET_CONTENT =205;
  const HTTP_PARTIAL_CONTENT =206;
  const HTTP_MOVED_PERMANENTLY=301;
  const HTTP_FOUND =302;
  // 303: Redirect after save or post.
  const HTTP_SEE_OTHER =303;
  const HTTP_NOT_MODIFIED =304;
  const HTTP_USE_PROXY =305;
  const HTTP_TEMPORARY_REDIRECT =307;
  const HTTP_PERMANENT_REDIRECT =308;
  const HTTP_BAD_REQUEST =400;
  const HTTP_UNAUTHORIZED =401;
  const HTTP_PAYMENT_REQUIRED =402;
  const HTTP_FORBIDDEN =403;
  const HTTP_PAGE_NOT_FOUND =404;
  const HTTP_METHOD_NOT_ALLOWED =405;
  const HTTP_NOT_ACCEPTABLE =406;
  const HTTP_PROXY_AUTH_REQUIRED =407;
  const HTTP_REQUEST_TIMEOUT =408;
  const HTTP_CONFLICT =409;
  // After successful delete
  const HTTP_GONE =410;
  const HTTP_PRECONDITION_FAILED =412;
  const HTTP_URI_TOO_LONG =414;
  // 419: Pages expired response when the when the CSRF-validation fails.
  const HTTP_PAGE_EXPIRED =419;
  const HTTP_UNPROCESSABLE_CONTENT =422;
  const HTTP_INTERNAL_SERVER_ERROR =500;
  const HTTP_NOT_IMPLEMENTED =501;
  const HTTP_SERVICE_UNAVAILABLE =503;

  public function __construct() {
  }

  public function __destruct() {
  }

  /**
   * @return ResponseCode
   */
  public static function getInstance() : ResponseCode {
    return new ResponseCode();
  }
}