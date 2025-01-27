<?php
namespace Common\Classes\Import;
use Exception;
use Common\Classes\Import\CSVFileImporter;
use Common\Classes\Model\HandledProduct;

/**
 * Filename  : handled_product_importer.class.php
 * Language     : PHP v7.x
 * Author(s)    : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 * Date created : IMA, 06/09-2016
 * Last modified: IMA, 06/09-2016
 * 
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * Wraps handling of import of CSV-files - Comma-Seperated-Values (CVS).
 * Remember that, if the MySQL-server is setup with the 'secure-file-priv',
 * Then the csv-files need to be in a special directory for MySQL to be able
 * to read the csv-file. Where that place is you can look up using the SQL below.
 *
 * select @@GLOBAL.secure_file_priv AS SECURE_LOCATION;
 *
 * @example:
 * // Import the CSV-file.
 * $importFile = PATH_IMPORT_FILES .'Product.csv';
 * $csvFileImporter = CSVFileImporter::getInstance($importFile, ';');
 * $csvFileImporter->importFile_loadIntoDBTable($baseTable, $dbPDOConnection);
 * $wasSuccessful = $csvFileImporter->importFile();
*/

class HandledProductImporter extends CSVFileImporter /* implements  */
{ 
  private $importedRecord;
 
   /**
    * @param string $p_importFile
    * @param string $p_valueDelimiter Default ','.
    */
   public function __construct($p_importFile, $p_valueDelimiter =',') {
      parent::__construct($p_importFile, $p_valueDelimiter);
   }

   public function __destruct() {
      parent::__destruct();
   }

   /**
    * @param string $p_importFile
    * @param string $p_valueDelimiter
    *
    * @return HandledProductImporter
    */
   public static function getInstance($p_importFile, $p_valueDelimiter =',') : HandledProductImporter {
      return new HandledProductImporter($p_importFile, $p_valueDelimiter);
   }

   /**
    * @param StdController $p_ctrlObj
    * @return boolean
    */
   public function importFile_loadEntireFile(StdController $p_ctrlObj)
   {
      $this->setAttr_ctrlInstance($p_ctrlObj);

      $arrPersistDataMethod = $this->getAttr_method_persist_data();
      $csvFileHandlerObj = $this->getAttr_csv_file_handler();
      $arrCSVContentLines = $csvFileHandlerObj->parseCSVFile($arrFields);

      if (is_array($arrCSVContentLines)) {
        foreach ($arrCSVContentLines as $curContentLine) {
          $arrRecordContent = $csvFileHandlerObj->getRecordContent_asAssocArray($curContentLine, $arrFields);
          $wasSuccesful = $this->saveImportedRecord($arrRecordContent, $p_ctrlObj);
        } // Each record
      } else {
        trigger_error('No data was returned from the parseing of the the CSV-file', E_USER_NOTICE);
      }
   } // method importFile_loadEntireFile

   /**
    * The method to overwrite in the inherited version of the class.
    * @param array
    * @param StdController
    *
    * @return bool
    */
   public function saveImportedRecord($p_arrImportedRecord =null, $p_ctrlObj)
   {
      $handledProductObj = HandledProduct::getInstance();
      $handledProductObj->markAsInserted();
      if (array_key_exists('handling_type', $p_arrImportedRecord)) {
        $handledProductObj->setAttr_handling_type($p_arrImportedRecord['handling_type']); 
      } else {
        $handledProductObj->setAttr_handling_type();
      }

      if (array_key_exists('product_serial_number', $p_arrImportedRecord)) {
        $handledProductObj->setAttr_product_serial_number($p_arrImportedRecord['product_serial_number']); 
      }

      if (array_key_exists('product_date_sent', $p_arrImportedRecord)) {
        $handledProductObj->setAttr_product_date_sent($p_arrImportedRecord['product_date_sent']);
      }

      if (array_key_exists('product_was_demo', $p_arrImportedRecord)) {
        $handledProductObj->setAttr_product_was_demo($p_arrImportedRecord['product_was_demo']);
      } else {
        $handledProductObj->setAttr_product_was_demo();
      }

      if (array_key_exists('user_id_created', $p_arrImportedRecord)) {
        $handledProductObj->setAttr_user_id_created($p_arrImportedRecord['user_id_created']);
      } else {
        // Default something.
        $handledProductObj->setAttr_user_id_created(2); // Test user
      }

      $arrToSave[] = $handledProductObj;
      return $handledProductObj->save($arrToSave, FALSE, $p_ctrlObj);
   }
} // End class