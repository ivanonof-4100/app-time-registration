<?php
namespace Common\Classes\Import;
use Exception;
use Common\Classes\FileHandler;

/**
 * Filename  : csv_file_handler.class.php
 * Language     : PHP v7.x
 * Developer(s) : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 * Date created : IMA, 06/09-2016
 * Last modified: IMA, 06/09-2016
 * 
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * Wraps basic handling of data-files in the format of Comma-Seperated-Values (CVS).
 *
 * @example:
 * $csvFileHandler = CSVFileHandler::getInstance('test.csv');
*/

class CSVFileHandler extends FileHandler
{
   const CSV_DEFAULT_DELIMITER = ',';

   /**
    * @var string
    */
   private $csv_file_name;
   private $csv_file_delimiter;
   private $is_file_parsed;

   /**
    * @param string $p_fileName
    * @param string $p_fileDelimiter Default ','
    */
   public function __construct(string $p_fileName, string $p_fileDelimiter =self::CSV_DEFAULT_DELIMITER) {
      parent::__construct();
      $this->setAttr_csv_file_name($p_fileName);
      $this->setAttr_csv_file_delimiter($p_fileDelimiter);  
   }

   public function __destruct() {
      parent::__destruct();
   }

   public function __clone() {
      parent::__clone();
   }

   /**
    * @param string $p_fileName
    * @param string $p_fileDelimiter Default ','.
    * @return CSVFileHandler
    */
   public static function getInstance(string $p_fileName, string $p_fileDelimiter =self::CSV_DEFAULT_DELIMITER) : CSVFileHandler {
      return new CSVFileHandler($p_fileName, $p_fileDelimiter);
   }

   // Getter and setter functions.

   private function setAttr_csv_file_name(string $p_fileName ='') : void {
      $this->csv_file_name = (string) $p_fileName;
   }

   public function getAttr_csv_file_name() : string {
      return $this->csv_file_name;
   }

   // Service methods.

   /**
    * @return string
    */
   public function getFilename() : string {
      return $this->getAttr_csv_file_name();
   }

   /**
    * @param string $p_fileDelimiter
    */
   private function setAttr_csv_file_delimiter(string $p_fileDelimiter =self::CSV_DEFAULT_DELIMITER) : void {
      $this->csv_file_delimiter = (string) $p_fileDelimiter;
   }

   /**
    * @return string
    */
   public function getAttr_csv_file_delimiter() : string {
      return $this->csv_file_delimiter;
   }

   /**
    * @param bool $p_isParsed Default FALSE.
    */
   private function setAttr_is_file_parsed(bool $p_isParsed =FALSE) : void {
      $this->is_file_parsed = (boolean) $p_isParsed;
   }

   /**
    * @return bool
    */
   public function getAttr_is_file_parsed() : bool {
      return $this->is_file_parsed;
   }

   /**
    * @return bool
    */
   public function isFileParsed() : bool {
      return $this->getAttr_is_file_parsed();
   }

   /**
    * @return array
    * @throws Exception
    */
   public function parseCSVFile(&$pbr_fileHeader) : array {
      $filename = $this->getAttr_csv_file_name();
      if (self::doesFileExists($filename)) {
        if (self::isRegularFile($filename) && self::isReadable($filename)) {
          try {
            // Okay lets parse the CSV-file.
            $fileContent = file_get_contents($filename, FILE_USE_INCLUDE_PATH);
            $arrFileContentLines = str_getcsv($fileContent, PHP_EOL);
            $fileHeader = array_shift($arrFileContentLines);
            $pbr_fileHeader = $this->extractHeaderFields($fileHeader);
            return $arrFileContentLines;
          } catch (Exception $e) {
            throw new Exception($e->getMessage());
          }
        } else {
          trigger_error(sprintf('File %s was NOT readable ...', $filename), E_USER_ERROR);
        }
      } else {
        trigger_error('The import-CSV file ('. $filename .') was not found in the file-system.', E_USER_WARNING);
      }
   }

   /**
    * @param string $p_headerContent Default blank.
    * @return array
    */
   private function extractHeaderFields(string $p_headerContent ='') {
      return explode($this->getAttr_csv_file_delimiter(), $p_headerContent);
   }

   /**
    * Returns the split array using the CSV-file delimiter to split the content.
    *
    * @param string $p_recordContent
    * @param array $p_arrFields
    * @return array
    */
   public function getRecordContent_asAssocArray($p_recordContent, $p_arrFields) {
      $arrRecordContent = explode($this->getAttr_csv_file_delimiter(), $p_recordContent);
      $arrRecord = array();
      while ($curFieldName = array_shift($p_arrFields))
      {
        if (!empty($curFieldName)) {
          $arrRecord[$curFieldName] = array_shift($arrRecordContent); 
        }
      } // while-loop

      return $arrRecord;
   }
} // End class