<?php
/**
 * Filename     : antispam_controller.class.php
 * Language     : PHP v5.x
 * Date created : 16/10-2016, IMA
 * Last modified: 16/10-2016, IMA
 * Developers   : @author IMA Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description:
 *  The startpage-controller.
 */
require_once(PATH_COMMON_CONTROLLERS .'std_controller.class.php');
require_once(PATH_COMMON_RENDERS .'antispam_renderer.class.php');

class AntispamController extends StdController
{
  private $rendererObj;

  /**
   * @return StartpageController
   */
  public function __construct()
  {
     parent::__construct();

     // Init-method of dependencies that is required.
     $this->initDependencies();

     $arrInputParam_print = $this->retriveInputParameter('print', 'boolean', '_GET');
     if ($arrInputParam_print['is_set'] && $arrInputParam_print['is_valid']) {
       $this->rendererObj = PageRenderer::getInstance($this->getInstance_languageFileHandler(), $arrInputParam_print['value']);
     } else {
       $this->rendererObj = PageRenderer::getInstance($this->getInstance_languageFileHandler());
     }

     // Load relavant language-files for the controller.
     $this->loadLanguageFiles();
  } // method __construct

  public function __destruct()
  {
     parent::__destruct();
  } // method __destruct

  /**
   * @return StartpageController
   */
  public static function getInstance()
  {
     return new StartpageController();
  } // method getInstance

  public function getInstance_rendererObj()
  {
     return $this->rendererObj;
  } // method getInstance_rendererObj

  public function loadLanguageFiles()
  {
     if (DEBUG) {
       $this->addDebugMessage(__METHOD__ .': Loading relevant language-files ...', __FILE__, __LINE__);
     }

     $languageObj = $this->getInstance_languageFileHandler();
     $wasSuccessful = $languageObj->loadLanguageFile('startpage');
  } // method loadLanguageFiles

  public function displayStartpage()
  {
     $languageObj = $this->getInstance_languageFileHandler();
     $rendererObj = $this->getInstance_rendererObj($languageObj);
     $rendererObj->renderStartpage();
  } // method displayStartpage

 } // End class