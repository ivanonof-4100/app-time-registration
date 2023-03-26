<?php
namespace Common\Classes\Controller;

use Common\Classes\Controller\StdController;
use Common\Classes\Controller\StdControllerInterface;
use Common\Classes\Renderes\ErrorRenderer;
use Common\Classes\Renderes\PageRenderer;
use Common\Classes\StdApp;

/**
 * Filename     : error_controller.class.php
 * Language     : PHP v7.4, v7.2+
 * Date created : 06/05-2012, Ivan
 * Last modified: 10/01-2021, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * 
 * @copyright Copyright (C) 2012 by Ivan Mark Andersen
 *
 * Description:
 *  The error-controller.
 */
class ErrorController extends StdController
{
  protected $errorRendererObj;

  /**
   * Constructor
   */
  public function __construct(string $p_lang ='da', string $p_charset ='utf8', StdApp $p_appInstance) {
     parent::__construct($p_lang, $p_charset, $p_appInstance);

     $arrInputParam_print = $this->retriveInputParameter('print', 'boolean', '_GET');
     if ($arrInputParam_print['is_set'] && $arrInputParam_print['is_valid']) {
       $this->errorRendererObj = ErrorRenderer::getInstance($this->getInstance_languageFileHandler(), $arrInputParam_print['value']);
     } else {
       $this->errorRendererObj = ErrorRenderer::getInstance($this->getInstance_languageFileHandler());
     }

     // Load relevant language-files for the controller.
     $this->loadLanguageFiles();
  }

  public function __destruct() {
     parent::__destruct();
  }

  /**
   * @param string $p_lang Default 'da'
   * @param string $p_charset Default 'utf8'
   * @param StdApp $p_appInstance
   *
   * @return ErrorController
   */
  public static function getInstance(string $p_lang ='da', string $p_charset ='utf8', StdApp $p_appInstance) : ErrorController {
     return new ErrorController($p_lang, $p_charset, $p_appInstance);
  }

  public function getInstance_renderer() {
     return $this->errorRendererObj;     
  }

  public function loadLanguageFiles() {
     $appInstance = $this->getAppInstance();
     if ($appInstance instanceof StdApp) {
       $customLangPath = $appInstance->getPath_languageFiles();
     } else {
       $customLangPath = FALSE;
     }

     // Get path of language-files relative to the codebase-path.
     $languageObj = $this->getInstance_languageFileHandler();
     $wasSuccessful = $languageObj->loadLanguageFile('error_handling', $customLangPath);
  }

  public function renderError($p_arrLastError) {
     $renderer = $this->getInstance_renderer();
     $renderer->renderOccurredError($p_arrLastError);
  }

  public function renderErrorMessage($p_errorMessage ='') {
     $renderer = $this->getInstance_renderer();
     $renderer->renderErrorMessage($p_errorMessage);
  }

  public function displayErrorPage404() {
     $pageRenderer = PageRenderer::getInstance($this->getInstance_languageFileHandler());
     $pageRenderer->renderPageNotFound();
  }

  public function displayErrorPage500($p_arrLastError) {
     $pageRenderer = PageRenderer::getInstance($this->getInstance_languageFileHandler());
     $pageRenderer->renderPageInternalError($p_arrLastError);
  }
} // End class