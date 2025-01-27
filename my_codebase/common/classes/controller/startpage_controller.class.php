<?php
use Exception;
use Common\Classes\StdApp;
use Common\Classes\Controller\StdController;
use Common\Classes\Controller\StdControllerInterface;

/**
 * Filename     : startpage_controller.class.php
 * Language     : PHP v7.4+
 * Date created : 24/01-2024, Ivan
 * Last modified: 24/01-2024, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * 
 * @copyright Copyright (C) 2024 by Ivan Mark Andersen
 *
 * Description:
 * The startpage-controller.
 */
// require_once(PATH_COMMON_RENDERS .'page_renderer.class.php');

class StartpageController extends StdController implements StdControllerInterface
{
  private $rendererObj;

  /**
   * @return StartpageController
   */
  public function __construct() {
     parent::__construct();

     // Initialize dependencies that is required.
//     $this->initDependencies();

     $arrInputParam_print = $this->retriveInputParameter('print', 'boolean', '_GET');
     if ($arrInputParam_print['is_set'] && $arrInputParam_print['is_valid']) {
       $this->rendererObj = PageRenderer::getInstance($this->getInstance_languageFileHandler(), $arrInputParam_print['value']);
     } else {
       $this->rendererObj = PageRenderer::getInstance($this->getInstance_languageFileHandler());
     }

     // Load relavant language-files for the controller.
     $this->loadLanguageFiles();
  }

  public function __destruct() {
     parent::__destruct();
  }

  /**
   * @return StartpageController
   */
  public static function getInstance() : StartpageController {
     return new StartpageController();
  }

  public function getInstance_renderer() {
     return $this->rendererObj;
  }

  public function loadLanguageFiles() : void {
     $languageObj = $this->getInstance_languageFileHandler();
     $wasSuccessful = $languageObj->loadLanguageFile('startpage');
  }

  public function displayStartpage() : void {
     $languageObj = $this->getInstance_languageFileHandler();
     $rendererObj = $this->getInstance_renderer();
     $rendererObj->renderStartpage();
  }
 } // End class