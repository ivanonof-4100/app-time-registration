<?php
/**
 * Script-name  : obj_renderer.class.php
 * Language     : PHP v5.x
 * Date created : 26/07-2007, Ivan
 * Last modified: 26/07-2007, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * 
 * @copyright Copyright (C) 2007 by Ivan Mark Andersen
 *
 * Description
 *  Encapsulates the use of an web-page wrapper class.
 *  Almost all the render-classes inherit from this render-class.
 */

require_once(PATH_COMMON_RENDERS .'webpage_wrapper.class.php');

/* An abstract renderer-class. */
abstract class ObjRenderer
{
  // Attributes
  protected $languageFileHandlerObj;
  protected $webPageWrapperObj;
  protected $isPrintPage;

  // Methods

  /**
   * Default constructor.
   *
   * @param resource $p_languageFileHandler
   * @param boolean $p_isPrintPage Default FALSE.
   */
  public function __construct($p_languageFileHandler, $p_isPrintPage =false) {
     $this->languageFileHandlerObj = $p_languageFileHandler;
     $this->setAttr_isPrintPage($p_isPrintPage);
     $this->webPageWrapperObj = new WebPageWrapper();
  } // method __construct

  /**
   * Default destructor.
   */
  public function __destruct() {
  } // method __destruct

  /**
   * Sets wheter or not the current page is what we call a printer-frindly-page.
   * @param boolean $p_isPrintPage TRUE if it is and FALSE if not.
   */
  protected function setAttr_isPrintPage($p_isPrintPage =false) {
     $this->isPrintPage = (boolean) $p_isPrintPage;
  } // method setAttr_isPrintPage

  /**
   * Returns a boolean result on wheter or not the current page is a printer-friendly web-page.
   * @return boolean A boolean result on wheter or not the current page is a printer-friendly page. - true says it is an false does not.
   */
  public function getAttr_isPrintPage() {
     return $this->isPrintPage;
  } // method getAttr_isPrintPage

  /**
   * @return LanguagefileHandler
   */
  public function getInstance_languageFileHandler() {
     return $this->languageFileHandlerObj;
  } // method getInstance_languageFileHandler

  /**
   * @return WebPageWrapper
   */
  protected function getInstance_webPageWrapper() {
     return $this->webPageWrapperObj;
  } // method getInstance_webPageWrapper

  /**
   * Renders a web-page with content.
   * 
   * @param string $p_pageContent
   * @param string $p_pageTitle
   */
  public function renderPage($p_pageContent, $p_pageTitle) {
     if (isset($_SESSION['session_handler'])) {
  	   $sessionHandlerObj = $_SESSION['session_handler'];
   	   $siteHandlerObj = $sessionHandlerObj->get('site_handler');
   	   $codebaseRegistryObj = $siteHandlerObj->getInstance_codebaseRegistry();

   	   $outputBufferObj = $codebaseRegistryObj->getInstance_outputBuffer();
   	   if (is_object($outputBufferObj)) {
         $fetchedOutput = $outputBufferObj->stopOutputBuffering(TRUE);
   	   } else {
         $fetchedOutput = '';
   	   }
     } else {
   	   $fetchedOutput = '';
   	   trigger_error('No session-handler was accessable ...', E_USER_ERROR);
     }

     $webPageWrapperObj = $this->getInstance_webPageWrapper();

     // Displays the full web-page with style-sheet and all.
     $webPageWrapperObj->setPageTitle($p_pageTitle);
     $webPageWrapperObj->setPageContent($p_pageContent .'<span id="details">'. $fetchedOutput .'</span>');

     $webPageWrapperObj->displayWrappedPage($this->getInstance_languageFileHandler(), $this->getAttr_isPrintPage());
  } // method renderPage

  /**
   * Adds a debug-message during code-execution for content-classes.
   * 
   * @param string $p_debugText
   * @param string $p_filename Optional use __FILE__ default blank.
   * @param int|boolean $p_lineNumber Optional use __LINE__ default FALSE.
   */
  public static function addDebugMessage($p_debugText, $p_filename ='', $p_lineNumber =FALSE) {
  	 require_once(PATH_CLASSES .'site_handler.class.php');
     $siteHandlerObj = SiteHandler::getInstance_siteHandler();
     if ($siteHandlerObj instanceof SiteHandler) {
       $codebaseRegistryObj = $siteHandlerObj->getInstance_codebaseRegistry();
       $debugHandlerObj = $codebaseRegistryObj->getInstance_debugHandler();

       // Add debug-message.
       $debugHandlerObj->addMessage(DEBUG_LEVEL_RENDERER, $p_debugText, $p_filename, $p_lineNumber);
     } else {
       trigger_error('It was not possible to access the site-handler instance ...', E_USER_ERROR);
     } 
  } // method addDebugMessage
} // End class