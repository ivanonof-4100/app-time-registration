<?php
namespace Common\Classes\Controller;

use Exception;
use Common\Classes\LanguagefileHandler;
use Common\Classes\Controller\StdController;
use Common\Classes\Renderes\ErrorRenderer;
use Common\Classes\StdApp;

/**
 * Filename     : error_controller.class.php
 * Language     : PHP v7.4
 * Date created : 06/05-2012, Ivan
 * Last modified: 28/05-2023, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * 
 * @copyright Copyright (C) 2023 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * My error-controller.
 */
class ErrorController extends StdController
{
  protected $rendererInstance;

  /**
   * Constructor
   */
  public function __construct(string $p_lang =APP_LANGUAGE_IDENT, string $p_charset ='utf8', StdApp $p_appInstance) {
    parent::__construct($p_appInstance);
    $this->rendererInstance = ErrorRenderer::getInstance(LanguagefileHandler::getInstance(FALSE, $p_lang, $p_charset));
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

  /**
   * @return ErrorRenderer
   */
  public function getInstance_renderer() : ErrorRenderer {
     return $this->rendererInstance;
  }

  public function loadLanguageFiles() : void {
     $appInstance = $this->getAppInstance();
     if ($appInstance instanceof StdApp) {
       $customLangPath = $appInstance->getPath_languageFiles();
     } else {
       $customLangPath = FALSE;
     }

     // Get path of language-files relative to the codebase-path.
     $rendererInstance = $this->getInstance_renderer();
     $languageObj = $rendererInstance->getInstance_languageFileHandler();
     // Load lang-file
     $wasSuccessful = $languageObj->loadLanguageFile('error_handling', $customLangPath);
   }

   /**
    * Initialize dependencies and the registry of the web-app.
    * @return void
    */
   public function initalizeDependencies() : void {
     // Start output-buffering.
     $rendererInstance = $this->getInstance_renderer();
     $rendererInstance->startOutputBuffering();
     $arrSettings = $this->getLoadedSettings();
     if (array_key_exists('app_lang_supported', $arrSettings)) {
       // Set supported languages
       $instanceRenderer = $this->getInstance_renderer();
       $instanceRenderer->setAttr_arrLangs($arrSettings['app_lang_supported']);
     }

     try {
       // Connect to database and start session-handling.
       $this->initDependencies($arrSettings);
     } catch (Exception $e) {
       // Handled errors.
       $rendererInstance = $this->getInstance_renderer();
       $rendererInstance->renderHandledAlert($e->getMessage());
       exit(0);
     }
  }

  public function renderError($p_arrLastError) : void {
     $renderer = $this->getInstance_renderer();
     $renderer->renderOccurredError($p_arrLastError);
  }

  public function renderErrorMessage($p_errorMessage) {
     $renderer = $this->getInstance_renderer();
     $renderer->renderErrorMessage($p_errorMessage);
  }

  public function displayError_page404() : void {
    $this->initalizeDependencies();
    // Get the active database-connection from the codebase-registry.
    $codebaseRegistry = $this->getInstance_codebaseRegistry();
    $dbAbstractionInstance = $codebaseRegistry->getInstance_dbConnection();

    $rendererInstance = $this->getInstance_renderer();
    $rendererInstance->renderPage_pageNotFound($dbAbstractionInstance);
  }

  public function displayError_page500($p_arrLastError) : void {
    $this->initalizeDependencies();
    // Get the active database-connection from the codebase-registry.
    $codebaseRegistry = $this->getInstance_codebaseRegistry();
    $dbAbstractionInstance = $codebaseRegistry->getInstance_dbConnection();

    $rendererInstance = $this->getInstance_renderer();
    $rendererInstance->renderPage_internalError($dbAbstractionInstance, $p_arrLastError);
  }
} // End class