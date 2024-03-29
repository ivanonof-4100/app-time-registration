<?php
namespace Common\Classes\Renderes;

use Common\Classes\LanguagefileHandler;
use Common\Classes\OutputBuffer;
use Common\Classes\Renderes\Template;
// use Common\Classes\Model\Menu;

/**
 * Script-name  : std_renderer.class.php
 * Language     : PHP v7.x
 * Date created : IMA, 15/08-2016
 * Last modified: IMA, 15/08-2016
 * Developers   : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description
 *  Rendering of pages.
 */
class StdRenderer
{
   /**
    * @var LanguagefileHandler
    */
   protected $languageFileHandler;

   /**
    * @var bool
    */
   protected $isPrintPage;

   protected $outputBuffer;

  /**
   * Default constructor.
   *
   * @param resource $p_languageFileHandler
   * @param bool $p_isPrintPage Default FALSE.
   */
  public function __construct($p_languageFileHandler, bool $p_isPrintPage =false) {
     $this->languageFileHandler = $p_languageFileHandler;
     $this->setAttr_isPrintPage($p_isPrintPage);
  }

  public function __destruct() {
  }

  /**
   * Sets wheter or not the current page is what we call a printer-frindly-page.
   * @param boolean $p_isPrintPage TRUE if it is and FALSE if not.
   */
  protected function setAttr_isPrintPage($p_isPrintPage =false) {
     $this->isPrintPage = (boolean) $p_isPrintPage;
  }

  /**
   * Returns a boolean result on wheter or not the current page is a printer-friendly web-page.
   * @return bool A boolean result on wheter or not the current page is a printer-friendly page. - true says it is an false does not.
   */
  public function getAttr_isPrintPage() : bool {
     return $this->isPrintPage;
  }

  /**
   * @return LanguagefileHandler
   */
  public function getInstance_languageFileHandler() : LanguagefileHandler {
     return $this->languageFileHandler;
  }

  public function startOutputBuffering() : void {
      // Start using buffered-output.
      $this->outputBuffer = OutputBuffer::getInstance(FALSE, 'UTF-8', 'UTF-8');
      $this->outputBuffer->startOutputBuffering();
  }

  public function stopOutputBuffering() : void {
      if (is_object($this->outputBuffer) && $this->outputBuffer instanceof OutputBuffer) {
        $this->outputBuffer->stopOutputBuffering();
      } else {
        trigger_error(__METHOD__ .': outputBuffer was not an instance of OutputBuffer ...', E_USER_ERROR);
      }
  }

  /**
   * @return string
   */
  public static function getDomainTitle($p_pageTitle ='') : string {
     return sprintf(APP_DOMAIN_TITLE, $p_pageTitle);
  }

  public function getMimeType_toUse() : string {
      // If the web-browser of the client supports XHTML, we want to serve the page in XHTML insted of HTML.
      $isXHTMLSupported = stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml');
      if ($isXHTMLSupported) {
        // The browser of the client supports XHTML, lets serve the page in XHTML.
        $mimeType = 'application/xml';
        // $mimeType = 'application/xhtml+xml';
      } else {
        // Default to HTML.
        $mimeType = 'text/html';
      }
      return $mimeType;
  }

  protected function sendResponseHeaders($p_mimeType ='text/html', $p_contentCharset ='UTF-8') {
     // Send raw HTTP-header before displaying the page.
     header(sprintf('Content-Type:%s; charset=%s', $p_mimeType, $p_contentCharset));
  }

  /**
   * Wraps everything in a nice HTML5 page.
   * @param string $p_pageTitle
   * @param string $p_pageMetaDescription Default blank.
   * @param string $p_pageMetaKeywords Default blank.
   * @param string $p_mainContent Default blank.
   * @param string $p_sidebarContent Default blank.
   * 
   * @return void
   */
  public function displayAsPage(string $p_pageTitle,
                                string $p_pageMetaDescription ='',
                                string $p_pageMetaKeywords ='',
                                string $p_mainContent ='',
                                string $p_sidebarContent =''
                               ) : void {
    // Get the language-file handler.
    $languagefileHandler = $this->getInstance_languageFileHandler();
    // $menu = Menu::retriveMenuData_byTagName()

    $template = Template::getInstance('std_page_html5.tpl');

    // Send the variables to the template.
    $template->assign('pageLangIdent', $languagefileHandler->getLanguageIdent());
    $template->assign('pageTitle', $p_pageTitle);
    $template->assign('pageDomainTitle', self::getDomainTitle($p_pageTitle));
    $template->assign('pageMetaDescription', $p_pageMetaDescription);
    $template->assign('pageMetaKeywords', $p_pageMetaKeywords);
    $template->assign('mainContent', $p_mainContent);
    $template->assign('sidebarContent', $p_sidebarContent);

    // Send HTTP response-header and display the page.
    $this->sendResponseHeaders();
    $template->display();
    $this->stopOutputBuffering();
  }
} // End class