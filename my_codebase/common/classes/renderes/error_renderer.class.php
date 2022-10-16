<?php
namespace Common\Classes\Renderes;

use Common\Classes\LanguagefileHandler;
use Common\Classes\Renderes\StdRenderer;

/**
 * Script-name  : error_renderer.class.php
 * Language     : PHP v5.x
 * Date created : 06/05-2012, Ivan
 * Last modified: 19/08-2016, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description
 *  Rendering of error-information.
 */
class ErrorRenderer extends StdRenderer
{
  // Attributes
  protected $errorColor;

  // Methods

  /**
   * @param resource $p_languageFileHandlerObj
   * @param boolean $p_isPrintPage
   *
   * @return ErrorRenderer
   */
  public function __construct($p_languageFileHandlerObj, $p_isPrintPage =false) {
     parent::__construct($p_languageFileHandlerObj, $p_isPrintPage);
     $this->errorColor = ini_get('highlight.default');
  }

  public function __destruct() {
     parent::__destruct();
  }

  /**
   * @return ErrorRenderer
   */
  public static function getInstance($p_languageFileHandlerObj, $p_isPrintPage =false) : ErrorRenderer {
     return new ErrorRenderer($p_languageFileHandlerObj, $p_isPrintPage);
  }

  protected function getAttr_errorColor() {
     return $this->errorColor;
  }

  public function renderOccurredError($p_arrLastError) {
     if (empty($p_arrLastError)) {
       $p_arrLastError = error_get_last();
     }

     $languageFileHandlerInstance = $this->getInstance_languageFileHandler();
     if ($languageFileHandlerInstance instanceof LanguagefileHandler) {
       // Make sure that all relevant language-files are loaded.
       $languageFileHandlerInstance->loadLanguageFile('error_handling');
     }

     // Set the page-title.
     $pageTitle = $languageFileHandlerInstance->getEntryContent('ERROR_OCCURRED_TITLE');
	  $errorTitle = $languageFileHandlerInstance->getEntryContent('ERROR_INFORMATION');

     // Setup the Smarty-template to use.
     // $templateObj = Template::getInstance('error-standard.frontend.tpl', PATH_TEMPLATES_STANDARD);
     $templateObj = Template::getInstance('standard_error_message.tpl', PATH_TEMPLATES_DOMAIN);

     // Send data to template.
     $templateObj->assign('pageDomainTitle', sprintf(APP_DOMAIN_TITLE, $pageTitle));
     $templateObj->assign('pageTitle', $pageTitle);
     $templateObj->assign('errorTitle', $errorTitle);
     $templateObj->assign('errorColor', 'red');
     $templateObj->assign('errorMessage', $p_arrLastError['message']);
	  $templateObj->assign('errorFile', $p_arrLastError['file']);
	  $templateObj->assign('errorLine', $p_arrLastError['line']);

     // Render the full page.
     // $this->renderPage($templateObj->fetch(), $pageTitle);
     $templateObj->display();
  }

  public function renderErrorMessage($p_errorMessage) {
     $languageHandlerObj = $this->getInstance_languageFileHandler();

     // Set the page-title.
     $pageTitle = $languageHandlerObj->getEntryContent('ERROR_OCCURRED_TITLE');

     // Setup the Smarty-template to use.
     $templateObj = Template::getInstance('standard_error_message.tpl', PATH_TEMPLATES_DOMAIN);

     // Send data to template.
     $templateObj->assign('pageDomainTitle', sprintf(APP_DOMAIN_TITLE, $pageTitle));
     $templateObj->assign('errorTitle', $pageTitle);
     $templateObj->assign('errorColor', $this->getAttr_errorColor());
     $templateObj->assign('errorMessage', $p_errorMessage);

     // Render the full page.
     // $this->renderPage($templateObj->fetch(), $pageTitle);
     $templateObj->display();
  }
} // Ends class