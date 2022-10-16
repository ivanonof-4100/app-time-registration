<?php
/**
 * Script-name  : csv_file_importer.class.php
 * Language     : PHP v7.x
 * Author(s)    : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 * Date created : IMA, 06/09-2016
 * Last modified: IMA, 06/09-2016
 * 
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description
 *  Wraps handling of import of CSV-files - Comma-Seperated-Values (CVS).
 *  Remember that, if the MySQL-server is setup with the 'secure-file-priv',
 *  Then the csv-files need to be in a special directory for MySQL to be able
 *  to read the csv-file. Where that place is you can look up using the SQL
 *  below.
 *
 *  select @@GLOBAL.secure_file_priv AS SECURE_LOCATION;
 *
 * @example:
 *  // Import the CSV-file.
 *  $importFile = PATH_IMPORT_FILES .'Product.csv';
 *  $csvFileImporterObj = CSVFileImporter::getInstance($importFile, ';');
 *  $csvFileImporterObj->importFile_loadIntoDBTable($baseTable, $dbPDOConnection);
 *  $wasSuccessful = $csvFileImporterObj->importFile();
*/

/*
If you don't care about how long it takes and how much memory it needs,
you can simply increase the values for this script.
Just add the following lines to the top of your script:

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '180');

With the function memory_get_usage() you can find out how much memory your script needs to find a good value for the memory_limit.

You might also want to have a look at fgets() which allows you to read a file line by line. I am not sure if that takes less memory, but I really think this will work. But even in this case you have to increase the max_execution_time to a higher value.
*/
require_once PATH_COMMON_IMPORT .'csv_file_handler.class.php';

class CSVFileImporter
{
   private $method_persist_data;
   /**
    * @var CSVFileHandler $csv_file_handler. 
    */
   private $csv_file_handler;

   /**
    * @var StdController $ctrlInstance
    */
   private $ctrlInstance;

   /**
    * @param string $p_importFile
    * @param string $p_valueDelimiter Default ','.
    *
    * @return CSVFileImporter
    */
   public function __construct($p_importFile, $p_valueDelimiter =',') {
      $this->setAttr_method_persist_data();
      $this->setAttr_csv_file_handler(CSVFileHandler::getInstance($p_importFile, $p_valueDelimiter));
   }

   public function __destruct() {
   }

   // Getter and setters
   /**
    * @param string $p_methodName Default 'saveImportedRecord'.
    */
   private function setAttr_method_persist_data($p_methodName ='saveImportedRecord') {
      $this->method_persist_data['non-callback'] = (string) $p_methodName;
      $this->method_persist_data['callback'] = sprintf('callback%s', ucfirst($p_methodName));
   } // method setAttr_method_persist_data

   /**
    * @return array
    */
   public function getAttr_method_persist_data() {
      return $this->method_persist_data;
   } // method getAttr_method_persist_data

   public function setAttr_csv_file_handler(CSVFileHandler $p_csvFileHandler) {
      $this->csv_file_handler = $p_csvFileHandler;
   }
 
   /**
    * @return CSVFileHandler
    */
   public function getAttr_csv_file_handler() : CSVFileHandler {
      return $this->csv_file_handler;
   }

   public function setAttr_ctrlInstance(StdController $p_ctrlObj) {
       $this->ctrlInstance = $p_ctrlObj;
   }

   public function getAttr_ctrlInstance() {
      return $this->ctrlInstance;
   } // method getAttr_ctrlInstance

   /**
    * @param string $p_importFile
    * @param string $p_valueDelimiter Default ','.
    *
    * @return CSVFileImporter
    */
   public static function getInstance(string $p_importFile, string $p_valueDelimiter =',') {
      return new CSVFileImporter($p_importFile, $p_valueDelimiter);
   } // method getInstance

   public function extractHeaderFields(string $p_headerContent ='') {
      $csvFileHandlerObj = $this->getAttr_csv_file_handler();
      $valueDelimiter = $csvFileHandlerObj->getAttr_csv_file_delimiter();
      return explode($valueDelimiter, $p_headerContent);
   } // method extractHeaderFields

   public function importFile_loadEntireFile(StdController $p_ctrlObj) : void {
      $handledProductImporterObj->setAttr_ctrlInstance($p_ctrlObj);

      $arrPersistDataMethod = $this->getAttr_method_persist_data();
      $csvFileHandlerObj = $this->getAttr_csv_file_handler();
      $arrCSVContentLines = $csvFileHandlerObj->parseCSVFile($arrFields);

      if (is_array($arrCSVContentLines)) {
        foreach ($arrCSVContentLines as $curContentLine) {
          $arrRecordContent = $csvFileHandlerObj->getRecordContentAsArray($curContentLine, $arrFields);
          $wasSuccesful = $this->$arrPersistDataMethod['non-callback']($arrFields, $arrRecordContent);
        } // Loop

        // Map execution of the storeImportedData for each element.
        // $arrResult = array_map($arrPersistDataMethod['callback']($), $arrCSVData);
      } else {
        trigger_error('No data was returned from the parseing of the the CSV-file', E_USER_NOTICE);
      }
   } // method importFile_loadEntireFile

   public function importFile_loadLineByLine() : void {
      $csvFileHandlerObj = $this->getAttr_csv_file_handler();
      $importFile = $csvFileHandlerObj->getFilename();
      $valueDelimiter = $csvFileHandlerObj->getAttr_csv_file_delimiter();
      $arrPersistDataMethod = $this->getAttr_method_persist_data();

      if (($fpHandle = $csvFileHandlerObj->openFile($importFile, 'r')) !== false) {
        // Get the first row, which contains the column-titles
        $header = fgetcsv($fpHandle);
        $arrFields = $this->extractHeaderFields($header);

        // Loop through the file line-by-line
        while (($lineData = fgetcsv($fpHandle)) !== false)
        {
          $arrRowValues = explode($valueDelimiter, $lineData);
          $persistDataMethod = $arrPersistDataMethod['non-callback'];
          if (method_exists($this, $persistDataMethod)) {
            $methodReflectionObj = new \ReflectionMethod($this, $persistDataMethod);
            // Check if requested method is public.
            if ($this->isPublic()) {
              $this->$persistDataMethod($arrFields, $arrRowValues);
            } else {
              // Trigger error.
              trigger_error(__METHOD__ .': Method '. $persistDataMethod .' is not public', E_USER_ERROR);
            }
          } else {
            trigger_error(__METHOD__ .': Method '. $persistDataMethod .' is not implemented', E_USER_ERROR);
          }

          unset($lineData);
        } // End while

        $csvFileHandlerObj->closeFile();
      }
   } // method importFile_lineByLine

   /**
    * Make sure, that this script called as Command-Line-Interface (CLI)
    * not via web-interface. Then no execution time-limit will affect it.
    * And do not keep parsed results forever but write them down immediately.
    * so you will not be affected by the memory-limit either.
    *
    * @param string $p_tableName
    * @param PDO $p_dbPDOConnection
    * @return int|boolean
    */
   public function importFile_loadIntoDBTable($p_tableName, PDO $p_dbPDOConnection) {
      $csvFileHandlerObj = $this->getAttr_csv_file_handler();
      $importFile = $csvFileHandlerObj->getFilename();
      $valueDelimiter = $csvFileHandlerObj->getAttr_csv_file_delimiter();

      $sql = "LOAD DATA INFILE '". $importFile ."'
        REPLACE INTO TABLE ". $p_tableName ." FIELDS TERMINATED BY '". $valueDelimiter ."' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES";
      try {
        return $p_dbPDOConnection->exec($sql);
      } catch(Exception $e) {
        echo $e->getMessage();
      }
   } // method importFile_intoDBTable
} // End class