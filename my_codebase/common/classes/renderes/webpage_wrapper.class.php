<?php
/**
 * Script-name  : webpage_wrapper.class.php
 * Language     : PHP v5.x
 * Date created : IMA, 31/07-2007
 * Last modified: IMA, 07/04-2009
 * Developers   : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2009, 2011 by Ivan Mark Andersen
 *
 * Description
 *  The purpose of this class is to wrap web-page tags around the
 *  the actual content of the web-page and end up with a full web-page.
 */

require_once(PATH_COMMON_RENDERS .'template.class.php');

class WebPageWrapper
{
   // Attributes.
   private $frameTitle;
   private $pageTitle;
   private $pageContent;

   // Methods.

   /**
    * Default constructor.
    * @param string $p_pageContent
    * @param string $p_pageTitle
    *
    * @return WebPageWrapper
    */
   public function __construct($p_pageContent ='', $p_pageTitle ='')
   {
      $this->setPageTitle($p_pageTitle);
   } // method __constructor


   /**
    * Default destructor.
    */
   public function __destruct()
   {
   } // method __destructor


   /**
    * Sets the title of the web-page.
    * @param string $p_pageTitle The given title of the web-page.
    */
   public function setPageTitle($p_pageTitle ='')
   {
      // Set page-title.
      $this->pageTitle = trim($p_pageTitle);
      $this->setFrameTitle();
   } // method setPageTitle


   /**
    * Returns the current set title.
    * @return string The complete title of the web-page.
    */
   public function getPageTitle()
   {
      return $this->pageTitle;
   } // method getPageTitle


   /**
    * Sets the title of the web-page.
    */
   public function setFrameTitle()
   {
      $pageTitle = $this->getPageTitle();

      // Set page-title.
      if (!empty($pageTitle)) {
      	$this->frameTitle = (string) $pageTitle .' | '. SITE_DOMAIN_TITLE;
      }
      else {
        $this->frameTitle = (string) SITE_DOMAIN_TITLE;
      }
   } // method setFrameTitle


   /**
    * Returns current title of the frame.
    * @return string
    */
   public function getFrameTitle()
   {
      return $this->frameTitle;
   } // method getFrameTitle


   /**
    * Sets the content of a web-page.
    * @param string $p_pageContent Pre-formated content of the page.
    */
   public function setPageContent($p_pageContent ='')
   {
/*
require_once(PATH_CLASSES_RENDERS .'menu_renderer.class.php');
      $menuRendererObj = new MenuRenderer();
      $mainMenuOfSite = $menuRendererObj->fetchMainMenuOfSite();
*/
      // Setup the template to use.
      $templateObj = new Template('page_layout.tpl');

      // Send data to template.
      $templateObj->assign('formatedMainContent', $p_pageContent);
      $templateObj->assign('pageTitle', $this->getPageTitle());
      $templateObj->assign('mainMenuOfSite', $mainMenuOfSite);

      $this->pageContent = (string) $templateObj->fetch();
   } // method setPageContent


   /**
    * Returns the Pre-formated content of the page.
    * @return string Web-page content.
    */
   public function getPageContent()
   {
      return $this->pageContent;
   } // method getPageContent


   /**
    * Wraps the page-content in HTML- or XHTML-tags and displays the final web-page using a template.
    * @param boolean $p_asPrinterFriendlyPage TRUE or FALSE on wheter or not it is an printer-friendly web-page.
    */
   public function displayWrappedPage(LanguagefileHandler $p_languageFileHandlerObj, $p_asPrinterFriendlyPage =false)
   {
      $contentCharset = strtolower(SITE_DEFAULT_CHARSET);

      // If the web-browser of the client supports XHTML, we want to serve the page in XHTML insted of HTML.
      $isXHTMLSupported = stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml');
      if ($isXHTMLSupported) {
        // The browser of the client supports XHTML, lets serve the page in XHTML.
      	$contentType = 'xhtml+xml';
        // $contentType = 'application/xml';
//        $contentType = 'application/xhtml+xml';
      }
      else {
        // Default to HTML.
        $contentType = 'text/html';
      }
   
      // Send raw HTTP-header before displaying the page
      $this->sendResponseHeaders($contentType, $contentCharset);

      // Setup the template to use.
      if ($p_asPrinterFriendlyPage) {
        $templateObj = new Template('std_webpage_printer.tpl');
      }
      else {
        // Default.
      	$templateObj = new Template('std_webpage.html5.tpl');
        // $templateObj = new Template('std_webpage.tpl');
      }

      // Send data to template.
      $templateObj->assign('isXHTMLSupported', $isXHTMLSupported);
      $templateObj->assign('contentType', $contentType);
      $templateObj->assign('pageTitle', $this->getFrameTitle());
      $templateObj->assign('pageContent', $this->getPageContent());
      $templateObj->assign('pageCharset', $contentCharset);
      $templateObj->assign('pageLanguageIdent', $p_languageFileHandlerObj->getLanguageIdent());
      $templateObj->assign('debugContent', $this->getFormated_debugMessages());

      // Display the final page.
      $templateObj->display();
   } // method displayWrappedPage


   protected function sendResponseHeaders($p_contentType ='text/html', $p_contentCharset ='utf-8')
   {
      // Send raw HTTP-header before displaying the page.
      header('Content-Type: '. $p_contentType .'; charset='. $p_contentCharset);
      // header('Cache-Control: max-age=120');
   } // method sendResponseHeaders


   /**
    * Gets the generated debug-messages for the current page.
    * @return string
    */
   public function getFormated_debugMessages()
   {
     if (DEBUG) {
       require_once(PATH_COMMON_CLASSES .'debug_message_handler.class.php');
       $debugMessageHandlerObj = DebugMessageHandler::getInstance_fromSession();
       if (isset($debugMessageHandlerObj)) {
         return $debugMessageHandlerObj->getReportedMessages();
       } else {
         return '';
       }
     } else {
       return '';
     }
   } // method getFormated_debugMessages
} // End class