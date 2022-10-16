<?php
/**
 * Filename     : contact_controller.class.php
 * Language     : PHP v5+, v7+
 * Date created : 15/11-2016, Ivan
 * Last modified: 15/11-2016, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * 
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description:
 *  The contact-controller.
 */
require_once(PATH_COMMON_CONTROLLERS .'std_controller.class.php');
require_once(PATH_COMMON_RENDERS .'contact_page_renderer.class.php');

class ContactController extends StdController
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
       $this->rendererObj = ContactPageRenderer::getInstance($this->getInstance_languageFileHandler(), $arrInputParam_print['value']);
     } else {
       $this->rendererObj = ContactPageRenderer::getInstance($this->getInstance_languageFileHandler());
     }

     // Load relavant language-files for the controller.
     $this->loadLanguageFiles();
  } // method __construct

  public function __destruct()
  {
     parent::__destruct();
  } // method __destruct

  /**
   * @return ContactController
   */
  public static function getInstance()
  {
     return new ContactController();
  } // method getInstance

  public function getInstance_rendererObj()
  {
     return $this->rendererObj;
  } // method getInstance_rendererObj

  public function loadLanguageFiles()
  {
     if (DEBUG) {
       $this->addDebugMessage(__METHOD__ .': Going to load relevant language-files ...', __FILE__, __LINE__);
     }

     $languageObj = $this->getInstance_languageFileHandler();
     $wasSuccessful = $languageObj->loadLanguageFile('contact');
  } // method loadLanguageFiles

  public function displayContactPage()
  {
     $languageObj = $this->getInstance_languageFileHandler();
     $rendererObj = $this->getInstance_rendererObj($languageObj);
     $rendererObj->renderContactPage();
  } // method displayContactPage

 } // End class