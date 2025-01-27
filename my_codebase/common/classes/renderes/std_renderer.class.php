<?php
namespace Common\Classes\Renderes;

use Common\Classes\LanguagefileHandler;
use Common\Classes\OutputBuffer;
use Common\Classes\ResponseCode;
use Common\Classes\Renderes\Template;
use Common\Classes\Helper\MimeType;
use Exception;

/**
 * Filename     : std_renderer.class.php
 * Language     : PHP v7.x
 * Date created : IMA, 15/08-2016
 * Last modified: IMA, 03/06-2023
 * Developers   : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2023 by Ivan Mark Andersen
 *
 * Description:
 * Super-class which contains standard rendering of web-pages.
 */
class StdRenderer
{
  /**
   * @var LanguagefileHandler
   */
  protected $languageFileHandler;

  /**
   * @var string
   */
  protected $mainNavigation;

  /**
   * @var array Supported languages
   */
  protected $arrLangs;

  /**
   * @var OutputBuffer
   */
  protected $outputBuffer;

  /**
   * Default constructor.
   * @param resource $p_languageFileHandler
   */
  public function __construct($p_languageFileHandler) {
    $this->languageFileHandler = $p_languageFileHandler;
  }

  public function __destruct() {
  }

  /**
   * Sets the array of supported languages in the renderer.
   * @param array $p_arrLangs
   */
  public function setAttr_arrLangs(array $p_arrLangs) : void {
    $this->arrLangs = $p_arrLangs;
  }

  /**
   * @return array
   */
  public function getAttr_arrLangs() : array {
    return $this->arrLangs;
  }

  /**
   * @return LanguagefileHandler
   */
  public function getInstance_languageFileHandler() : LanguagefileHandler {
    return $this->languageFileHandler;
  }

  public function getCurrent_isoLangIdent() : string {
    $languageFileHandler = $this->getInstance_languageFileHandler();
    return $languageFileHandler->getLanguageIdent();
  }

  /**
   * @param string $p_mainNavigation
   */
  public function setMainNavigation(string $p_mainNavigation ='') : void {
    $this->mainNavigation = (string) $p_mainNavigation;
  }

  public function getMainNavigation() {
    return $this->mainNavigation;
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
  public static function getDomainTitle(string $p_pageTitle ='') : string {
    return sprintf(APP_DOMAIN_TITLE, $p_pageTitle);
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

  /**
   * Renders an handled alert-messages.
   * @param string $p_alertMesg Default blank.
   * @throws Exception
   * @return void
   */
  public function renderHandledAlert(string $p_alertMesg ='') : void {
     $alertTitle = 'System fejl';
     $template = Template::getInstance('alert_warning.tpl', Template::PATH_TEMPLATES_SITE);
     $template->assign('alertText', $p_alertMesg);
     $template->assign('dismissable', FALSE);

     try {
      $templateOutput = $template->fetch();
      $this->displayAsPage($alertTitle, '', '', $templateOutput);
    } catch (Exception $e) {
      // Re-throw exception.
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

  /**
   * Wraps everything in a nice HTML5 page.
   * @param string $p_pageTitle
   * @param string $p_pageMetaDescription Default blank.
   * @param string $p_pageMetaKeywords Default blank.
   * @param string $p_mainContent Default blank.
   * @param string $p_sidebarContent Default blank.
   *
   * @throws Exception
   * @return void
   */
  public function displayAsPage(string $p_pageTitle,
                                string $p_pageMetaDescription ='',
                                string $p_pageMetaKeywords ='',
                                string $p_mainContent ='',
                                string $p_sidebarContent ='',
                                int $p_useResponseCode =ResponseCode::HTTP_OK
                               ) : void {
    // Get the language-file handler.
    $languagefileHandler = $this->getInstance_languageFileHandler();

    $template = Template::getInstance('std_page_html5.tpl', Template::PATH_TEMPLATES_STD);
    // Send the variables to the template.
    $template->assign('mainNavigation', $this->getMainNavigation());
    $template->assign('pageLangIdent', $languagefileHandler->getLanguageIdent());
    $template->assign('pageTitle', $p_pageTitle);
    $template->assign('pageDomainTitle', self::getDomainTitle($p_pageTitle));
    $template->assign('pageMetaDescription', $p_pageMetaDescription);
    $template->assign('pageMetaKeywords', $p_pageMetaKeywords);
    $template->assign('mainContent', $p_mainContent);
    $template->assign('sidebarContent', $p_sidebarContent);
    $template->assign('arrConfigPaths', Template::getTemplatePaths());
    $template->assign('smartyVersion', Template::getSmartyVersion());

    // Send HTTP response-header
    $this->sendResponseHeaders($p_useResponseCode, MimeType::getMimeType_forObject(MimeType::OBJ_TYPE_PAGE, MimeType::PAGE_VARIANT_HTML5));
    try {
      // Display the page
      $template->display();
      $this->stopOutputBuffering();
    } catch (Exception $e) {
      $this->stopOutputBuffering();
      // Re-throw the exception.
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }
}