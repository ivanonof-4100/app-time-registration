<?php
namespace Common\Classes\Renderes;

use Common\Classes\LanguagefileHandler;
use Common\Classes\Renderes\StdRenderer;
use Common\Classes\Renderes\MenuRenderer;
use Common\Classes\Db\DBAbstraction;
use Common\Classes\ResponseCode;
use Exception;

/**
 * Filename  : error_renderer.class.php
 * Language     : PHP v7.x
 * Date created : 06/05-2012, Ivan
 * Last modified: 19/08-2016, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description
 * Rendering of error-information.
 */
class ErrorRenderer extends StdRenderer
{
  // Attributes
  protected $errorColor;

  // Methods

  /**
   * @param resource $p_languageFileHandler
   */
  public function __construct(LanguagefileHandler $p_languageFileHandler) {
     parent::__construct($p_languageFileHandler);
     $this->errorColor = ini_get('highlight.default');
  }

  public function __destruct() {
     parent::__destruct();
  }

  /**
   * @return ErrorRenderer
   */
  public static function getInstance(LanguageFilehandler $p_languageFileHandler, $p_isPrintPage =false) : ErrorRenderer {
    return new ErrorRenderer($p_languageFileHandler, $p_isPrintPage);
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
    $pageTitle = 'Bla';
    // $pageTitle = $languageFileHandlerInstance->getEntryContent('ERROR_OCCURRED_TITLE');
	  $errorTitle = 'Information';
    // $errorTitle = $languageFileHandlerInstance->getEntryContent('ERROR_INFORMATION');

    // Setup the Smarty-template to use.
    $template = Template::getInstance('standard_error_message.tpl', Template::PATH_TEMPLATES_STD);

    // Send data to template.
    $template->assign('pageDomainTitle', sprintf(APP_DOMAIN_TITLE, $pageTitle));
    $template->assign('pageTitle', $pageTitle);
    $template->assign('errorTitle', $errorTitle);
    $template->assign('errorColor', 'red');
    $template->assign('errorMessage', $p_arrLastError['message']);
	  $template->assign('errorFile', $p_arrLastError['file']);
	  $template->assign('errorLine', $p_arrLastError['line']);

    // Render the full page.
    // $this->renderPage($templateObj->fetch(), $pageTitle);
    $template->display();
  }

  public function renderErrorMessage(string $p_errorMessage) : void {
     $languageHandlerObj = $this->getInstance_languageFileHandler();

     // Set the page-title.
     $pageTitle = $languageHandlerObj->getEntryContent('ERROR_OCCURRED_TITLE');

     // Setup the Smarty-template to use.
     $template = Template::getInstance('standard_error_message.tpl', Template::PATH_TEMPLATES_SITE);

     // Send data to template.
     $template->assign('pageTitle', 'Error message');
     $template->assign('pageDomainTitle', sprintf(APP_DOMAIN_TITLE, $pageTitle));
     $template->assign('errorTitle', $pageTitle);
     $template->assign('errorColor', $this->getAttr_errorColor());
     $template->assign('errorMessage', $p_errorMessage);

     // Render the full page.
     // $this->renderPage($template->fetch(), $pageTitle);
     $template->display();
  }

  /**
   * @return string
   */
  public static function getScriptName() : string {
    return basename($_SERVER['SCRIPT_NAME'], '.php');
  }

  public function renderPage_pageNotFound(DBAbstraction $p_dbAbstraction) {
    // Load language-file
    $languagefileHandler = $this->getInstance_languageFileHandler();
    $languagefileHandler->loadLanguageFile('error_handling', APP_LANGUAGE_PATH);

    $menuRenderer = MenuRenderer::getInstance($languagefileHandler);
    $this->setMainNavigation($menuRenderer->render_mainMenu($p_dbAbstraction, $languagefileHandler->getLanguageIdent(), $this->getAttr_arrLangs()));

    // Get localized page-title.
    $pageTitle = $languagefileHandler->getEntryContent('ERROR_PAGE_NOTFOUND');
    $template = Template::getInstance('error_404.tpl', Template::PATH_TEMPLATES_STD);

    // Send the variables to the template.
    $template->assign('pageTitle', $pageTitle);
    $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
    $template->assign('pageMetaDescription', SITE_DOMAIN_NAME .', 404 fejl-side');
    $template->assign('scriptName', self::getScriptName());

    // Display
    $pageMetaDescription = 'Occured error page not found';
    $pageMetaKeywords = 'Web-app, page not found, 404';
    $this->displayAsPage($pageTitle, $pageMetaDescription, $pageMetaKeywords, $template->fetch(), '', ResponseCode::HTTP_PAGE_NOT_FOUND);
  }

  public function renderPage_internalError(DBAbstraction $p_dbAbstraction, $p_arrLastError) : void {
    // Load language-file
    $languagefileHandler = $this->getInstance_languageFileHandler();
    $languagefileHandler->loadLanguageFile('error_handling', APP_LANGUAGE_PATH);

    $menuRenderer = MenuRenderer::getInstance($languagefileHandler);
    $this->setMainNavigation($menuRenderer->render_mainMenu($p_dbAbstraction, $languagefileHandler->getLanguageIdent(), $this->getAttr_arrLangs()));
    
    // Get localized page-title.
    $pageTitle = 'Intern server-fejl';
    $template = Template::getInstance('error_500.tpl', Template::PATH_TEMPLATES_STD);

    // Send the variables to the template.
    $template->assign('pageTitle', $pageTitle);
    $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
    $template->assign('pageMetaDescription', SITE_DOMAIN_NAME .', 500 fejl-side');
    $template->assign('scriptName', self::getScriptName());
    $template->assign('errorTitle', 'An internal server-error occurred!');
    $template->assign('errorColor', 'red');

    if (is_array($p_arrLastError) && array_key_exists('message', $p_arrLastError)) {
      $template->assign('errorMessage', $p_arrLastError['message']);
    }
    if (is_array($p_arrLastError) && array_key_exists('file', $p_arrLastError)) {
      $template->assign('errorFile', $p_arrLastError['file']);
    }
    if (is_array($p_arrLastError) && array_key_exists('line', $p_arrLastError)) {
      $template->assign('errorLine', $p_arrLastError['line']);
    }

    try {
      $templateOutput = $template->fetch();
    } catch (Exception $e) {
      $templateOutput = $e->getMessage();
    }

    // Display
    $pageMetaDescription = 'Occured internal-error';
    $pageMetaKeywords = 'Web-app, internal-error, 500';
    $this->displayAsPage($pageTitle, $pageMetaDescription, $pageMetaKeywords, $templateOutput, '', ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
  }
}