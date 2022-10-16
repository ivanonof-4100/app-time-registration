<?php
/**
 * Script-name  : csv_file_handler.class.php
 * Language     : PHP v7.x
 * Developer(s) : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 * Date created : IMA, 06/09-2016
 * Last modified: IMA, 06/09-2016
 * 
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description
 *  Wraps basic handling of data-files in the format of Comma-Seperated-Values (CVS).
 *
 * @example:
 *  CSVFileHandler
 *
*/
require_once PATH_COMMON_CLASSES .'file_handler.class.php';

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
    * @param string|boolean $p_fileDelimiter Default boolean FALSE.
    * @return CSVFileHandler
    */
   public function __construct($p_fileName, $p_fileDelimiter =FALSE) {
      parent::__construct();
      $this->setAttr_csv_file_name($p_fileName);
      $this->setAttr_csv_file_delimiter($p_fileDelimiter);
      
   } // method __construct

   public function __destruct() {
      parent::__destruct();
   } // method __destruct

   public function __clone() {
      parent::__clone();
   } // method __clone

   /**
    * @param string $p_fileName
    * @param string|boolean $p_fileDelimiter Default Boolean FALSE.
    * @return CSVFileHandler
    */
   public static function getInstance($p_fileName, $p_fileDelimiter =FALSE) : CSVFileHandler {
      return new CSVFileHandler($p_fileName, $p_fileDelimiter);
   } // method getInstance

   // Getter and setter functions.

   private function setAttr_csv_file_name($p_fileName ='') : void {
      $this->csv_file_name = (string) $p_fileName;
   } // method setAttr_csv_file_name

   public function getAttr_csv_file_name() : string {
      return $this->csv_file_name;
   } // method getAttr_csv_file_name

   // Service methods.

   /**
    * @return string
    */
   public function getFilename() {
      return $this->getAttr_csv_file_name();
   } // method getFilename

   /**
    * @param string $p_fileDelimiter
    */
   private function setAttr_csv_file_delimiter($p_fileDelimiter =FALSE) : void {
      if ($p_fileDelimiter === FALSE) {
         $this->csv_file_delimiter = self::CSV_DEFAULT_DELIMITER;
      } else {
         $this->csv_file_delimiter = (string) $p_fileDelimiter;
      }
   } // method setAttr_csv_file_delimiter

   /**
    * @return string
    */
   public function getAttr_csv_file_delimiter() : string {
      return $this->csv_file_delimiter;
   } // method getAttr_csv_file_delimiter

   /**
    * @param bool $p_isFileParsed Default FALSE.
    */
   private function setAttr_is_file_parsed($p_isParsed =FALSE) : bool {
      $this->is_file_parsed = (boolean) $p_isPassed;  
   } // method setAttr_is_file_parsed

   /**
    * @return bool
    */
   public function getAttr_is_file_parsed() : bool {
      return $this->is_file_parsed;
   } // method getAttr_is_file_parsed

   /**
    * @return bool
    */
   public function isFileParsed() : bool {
      return $this->getAttr_is_file_parsed();
   } // method isFileFileParsed

   /**
    * @return array
    */
   public function parseCSVFile(&$pbr_fileHeader) {
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
          } catch (Execption $e) {
            echo $e->getMessage();
          }
        } else {
          trigger_error('File ('. $filename .') was not readable ...', E_USER_WARNING);   
        }
      } else {
        echo trigger_error('The import-CSV file ('. $filename .') was not found in the file-system.', E_USER_WARNING);
      }
   } // method parseCSVFile

   /**
    * @param string $p_headerContent Default blank.
    * @return array
    */
   private function extractHeaderFields(string $p_headerContent ='') {
      return explode($this->getAttr_csv_file_delimiter(), $p_headerContent);
   } // method extractHeaderFields

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
   } // method getRecordContent_asAssocArray
} // End class