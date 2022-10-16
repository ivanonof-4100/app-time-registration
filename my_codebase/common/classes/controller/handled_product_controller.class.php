<?php
/**
 * Filename     : handled_product_controller.class.php
 * Language     : PHP v7+
 * Date created : 19/01-2017, Ivan
 * Last modified: 19/01-2017, Ivan
 * Developers   : @author Ivan Mark Andersen <ima@dectel.dk>
 * 
 * @copyright Copyright (C) 2017 by Dectel A/S
 *
 * Description:
 *  The handled-product controller.
 */
require_once(PATH_COMMON_CONTROLLERS .'std_controller.class.php');
require_once(PATH_COMMON_RENDERS .'handled_product_renderer.class.php');
require_once(PATH_COMMON_MODEL .'handled_product.class.php');

class HandledProductController extends StdController
{
  private $rendererObj;

  /**
   * @return HandledProductController
   */
  public function __construct()
  {
     parent::__construct();

     // Init-method of dependencies that is required.
     $this->initDependencies();

     $arrInputParam_print = $this->retriveInputParameter('print', 'boolean', '_GET');
     if ($arrInputParam_print['is_set'] && $arrInputParam_print['is_valid']) {
       $this->rendererObj = HandledProductRenderer::getInstance($this->getInstance_languageFileHandler(), $arrInputParam_print['value']);
     } else {
       $this->rendererObj = HandledProductRenderer::getInstance($this->getInstance_languageFileHandler());
     }

     // Load relavant language-files for the controller.
     $this->loadLanguageFiles();
  } // method __construct

  public function __destruct()
  {
     parent::__destruct();
  } // method __destruct

  /**
   * @return HandledProductController
   */
  public static function getInstance()
  {
     return new HandledProductController();
  } // method getInstance

  public function getInstance_rendererObj()
  {
     return $this->rendererObj;
  } // method getInstance_rendererObj

  public function loadLanguageFiles()
  {
     $languageObj = $this->getInstance_languageFileHandler();
     $wasSuccessful = $languageObj->loadLanguageFile('handled_product');
  } // method loadLanguageFiles

  public function checkWarranty()
  {
     $arrInput_serialNumber = $this->retriveInputParameter('serial_number', 'string', '_GET');
     if ($arrInput_serialNumber['is_set'] && $arrInput_serialNumber['is_valid']) {
       // Input parameter is set and is valid - now check if the we got the record.
       $handlingId = HandledProduct::doesSerialNumberExists($arrInput_serialNumber['value'], $this);
       if ($handlingId) {
         $wasFound = TRUE;
         // Get the instance of the model-object.
         $handledProductObj = HandledProduct::getInstance_byObjId($handlingId, $this);
       } else {
         $wasFound = FALSE;
         $handledProductObj = null;
       }

       // Render the HTML-response.
       $languageObj = $this->getInstance_languageFileHandler();
       $rendererObj = $this->getInstance_rendererObj($languageObj);
       $rendererObj->renderWarrantyHTMLResponse($wasFound, $handledProductObj);
     } else {
       // Parameters is either not set or is not valid.
       trigger_error('No parameters was given ...', E_USER_ERROR);
     }
  } // method checkWarranty

  public function importHandledRecords()
  {
     $arrInput_importFile = $this->retriveInputParameter('import_file', 'string', '_GET');
     if ($arrInput_importFile['is_set'] && $arrInput_importFile['is_valid']) {
       // Start the import-process.
       // echo sprintf('File to import: %s', $arrInput_importFile['value']);
       require_once PATH_COMMON_IMPORT .'handled_product_importer.class.php';
       $importPath = PATH_CODEBASE_ROOT .'import-files'. PATH_DELIMITER;
       $handledProductImporterObj = HandledProductImporter::getInstance($importPath . $arrInput_importFile['value'], ',');

       $handledProductImporterObj->importFile_loadEntireFile($this);
     } else {
       echo 'Needed parameter is not specifyed ...';
     }
  } // method importHandledRecords

 } // End class