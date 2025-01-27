<?php
namespace Common\Classes;
use Common\Classes\CustomString;

/**
 * Filename     : output_buffer.class.php
 * Language     : PHP v7.x
 * Date created : 08/05-2013, Ivan
 * Last modified: 05/06-2023, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * 
 * @copyright Copyright (C) 2023 by Ivan Mark Andersen
 * 
 * Description:
 *  My custom output-buffer that wraps all the actions that you can do on a output-buffer.
 *  
 *  @example 1:
 *  // Start output-buffering.
 *  
 *  // Do stuff.
 *  
 *  // Stop output-buffering.
 *  // Output all of the buffer-content to the web-browser
*/

class OutputBuffer
{
  // Attributes
  /**
   * @var string
   */
  protected $encoding_charset_internal;

  /**
   * @var string
   */
  protected $encoding_charset_external;

  /**
   * @var bool Tells whether or not the use implicit rather than explicit flushing of the content of the output-buffer.
  */
  protected $use_implicit_flush;

  /**
   * @var CustomString
   */
  protected $buffer;

  // Methods

  /**
   * Default constructor
   *
   * @param string $p_internalEncoding Default 'UTF-8'.
   * @param string $p_externalEncoding Default 'UTF-8'.
   * @param bool $p_useImplicitFlush Default FALSE.
   *
   * @return OutputBuffer
  */
  public function __construct($p_useImplicitFlush =FALSE, $p_internalEncoding ='UTF-8', $p_externalEncoding ='UTF-8') {
     // Make sure that output-buffering is enabled.
     if (!self::isOutputBufferingEnabled()) {
       // Enable output-buffering.
       self::enableOutputBuffering();
     }
 
     $this->buffer = CustomString::getInstance('', $p_internalEncoding);

     if ($p_useImplicitFlush === TRUE) {
       $this->turnOn_implicitFlush();
     } else {
       $this->turnOff_implicitFlush();
     }

     $this->setAttr_encoding_charset_internal($p_internalEncoding);
     $this->setAttr_encoding_charset_external($p_externalEncoding);

     if ($this->isEncodingTransformationRequired()) {
       // Set encoding-standard to use for the output-buffer.
       iconv_set_encoding("internal_encoding", $this->getAttr_encoding_charset_internal());
       iconv_set_encoding("output_encoding", $this->getAttr_encoding_charset_external() .'//TRANSLIT');
     }
  }

  public function __destruct() {
     // Its not possible to clean an already cleaned output-buffer nor is it required when it has a zero length buffer.
     $bufferLength = $this->getBufferLength();
     if ($bufferLength >=1) {
       $this->clean();
     }
     // ob_end_flush();
  }

  public function __toString() : string {   
     return sprintf('buffer: %s, use_implicit_flush: %s', $this->buffer->__toString(), $this->use_implicit_flush);
  }

  // Setter and getter methods

  /**
   * getInstance
   *
   * @param string $p_internalEncoding Default 'UTF-8'.
   * @param string $p_externalEncoding Default 'UTF-8'.
   * @param bool $p_useImplicitFlush Default FALSE.
   *
   * @return OutputBuffer
  */
  public static function getInstance($p_useImplicitFlush =FALSE, $p_internalEncoding ='UTF-8', $p_externalEncoding ='UTF-8') : OutputBuffer {
     return new OutputBuffer($p_useImplicitFlush, $p_internalEncoding, $p_externalEncoding);
  }

  /**
   * Sets the buffer
   * @param string $p_bufferContent
   */
  protected function setAttr_buffer($p_bufferContent ='') : void {
  	 $this->buffer->setAttr_str($p_bufferContent);
  }

  /**
   * @return string
   */
  protected function getAttr_buffer() : string {
  	 return $this->buffer->getAttr_str();
  }

  protected function setAttr_encoding_charset_internal($p_encodingCharset ='UTF-8') {
     $this->encoding_charset_internal = (string) $p_encodingCharset;
  }

  protected function setAttr_encoding_charset_external($p_encodingCharset ='UTF-8') {
     $this->encoding_charset_external = (string) $p_encodingCharset;
  }

  protected function getAttr_encoding_charset_internal() : string {
     return $this->encoding_charset_internal;
  }

  protected function getAttr_encoding_charset_external() : string {
     return $this->encoding_charset_external;
  }

  /**
   * @return bool
   */
  protected function isEncodingTransformationRequired() : bool {
     return ($this->getAttr_encoding_charset_internal() != $this->getAttr_encoding_charset_external());
  }

  /**
   * This turns on the use of implicit flushing.
   * Implicit flushing will result in a flush operation after every output call,
   * So that explicit calls to flush() will no longer be needed.
   * 
   * @return void
   */
  protected function turnOn_implicitFlush() : void {
     $this->use_implicit_flush = (boolean) TRUE;
     ob_implicit_flush($this->use_implicit_flush);
  }

  /**
   * Turns off the use of implicit flushing.
   * Implicit flushing will result in a flush operation after every output call,
   * so that explicit calls to flush() will no longer be needed.
   *
   * @return void
   */
  protected function turnOff_implicitFlush() : void {
     $this->use_implicit_flush = (boolean) FALSE;
     ob_implicit_flush($this->use_implicit_flush);
  }

  // Services methods

  /**
   * @return bool Returns TRUE if the output-buffering is enabled, otherwise FALSE.
  */
  public static function isOutputBufferingEnabled() : bool {
     $outputBuffering = strtolower(ini_get('output_buffering'));
     return ($outputBuffering !== 'off');
  }

  public static function enableOutputBuffering() : void {
  	 $previousValue = ini_set('output_buffering', 'On');
  	 if ($previousValue === FALSE) {
      trigger_error(__METHOD__ .': It was NOT possible to enable output-buffering ...', E_USER_ERROR);
  	 }
  }

  /**
   * @return bool Returns TRUE, if the output-buffer use implicit-flushing, otherwise FALSE.
   */
  public function doesUseImplicitFlush() : bool {
     return $this->use_implicit_flush;
  }

  /**
   * Return the current length of the output-buffer.
   * @return int
  */
  public function getBufferLength() : int {
     return ob_get_length();
  }

  /**
   * @return bool
   */
  public static function doesBrowserSupport_compressedContent() : bool {
    // Check if there is any Accept-Encoding HTTP-header.
    if (!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
      return FALSE;
    } else {
      $arrSupportedEncodings = explode(',', $_SERVER['HTTP_ACCEPT_ENCODING']);
      if (!is_array($arrSupportedEncodings)) {
        return ($arrSupportedEncodings == 'gzip');
      } else {
        return array_key_exists('gzip', $arrSupportedEncodings);
      }
    }
  }
 
  /**
   * @return void
   */
  public function startOutputBuffering() : void {
  	  // If output buffering is not active, then start the output-buffering.
     if (ob_get_level() == 0) {
       if ($this->isEncodingTransformationRequired()) {
         // Start output-buffering using an iconv-handler to convert encoding.
         ob_start("ob_iconv_handler");
       } else {
         if (self::doesBrowserSupport_compressedContent()) {
           // Starting normal output-buffering without an iconv-handler.
           mb_http_output($this->getAttr_encoding_charset_internal());
           ob_start("ob_gzhandler");
         } else {
           // Starting normal output-buffering without an iconv-handler.
           mb_http_output($this->getAttr_encoding_charset_internal());
           ob_start("mb_output_handler");
         }
       }
     }
  }

  /**
   * Stops the output-buffering.
   * 
   * @param bool $p_fetchBufferOutput Default FALSE.
   * @return string|void
   */
  public function stopOutputBuffering(bool $p_fetchBufferOutput =FALSE) {
  	 if ($p_fetchBufferOutput) {
       $this->setAttr_buffer($this->fetchBufferContent());
       $wasSuccessful = $this->clean();
       return $this->getAttr_buffer();
  	 } else {
       // Else just flush and send the content of the output-buffer to the web-browser.
       $this->flush();
       if ($this->getBufferLength()) {
        $wasSuccessful = $this->clean();
       }
  	 }
  }

  /**
   * @return string
   */
  protected function fetchBufferContent() {
  	 return ob_get_contents();
  }

  /**
   * Get current contents of the output-buffer.
   * @return string|bool If output-buffering isn't active then FALSE is returned.
   */
  public function fetchContents() {
    return ob_get_contents();
  }

  /**
   * Flushes or sends the content of the output-buffer and turn off output-buffering. 
   * @return void
   */
  public function flush() : void {
    // We only need flush, if an output-buffer is active.
    if (ob_get_level() > 0) {
      ob_flush();
    }
  }

  /**
   * Get current buffer contents and delete current output buffer 
   * @return string|bool If output-buffering isn't active then FALSE is returned.
   */
  public function fetchContentsClean() {
    return ob_get_clean();
  }

  public function clean() {
    return ob_end_clean();
  }
} // End class