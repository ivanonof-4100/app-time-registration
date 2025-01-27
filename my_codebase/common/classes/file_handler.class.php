<?php
namespace Common\Classes;
use Exception;

/**
 * Filename     : file_handler.class.php
 * Language     : PHP v7.4
 * Date created : 03/11-2012, Ivan
 * Last modified: 16/05-2023, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * 
 * @copyright Copyright (C) 2023 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * Wraps basic file-handling for easy access to file-handling.
 *
 * @example #1:
 * $filename = '/etc/passwd';
 * if (FileHandler::isRegularFile($filename) && FileHandler::isReadable($filename)) {
 *   echo sprintf("File %s is a regular-file and its also readable ...", $filename);
 * }
 */
class FileHandler
{
   const UNIT_BYTES = 'B';
   const UNIT_KILOBYTES = 'KB';
   const UNIT_MEGABYTES = 'MB';
   const UNIT_GIGABYTES = 'GB';
   const UNIT_TERABYTES = 'TB';

   private $file_pointer = null;

   /**
    * @return FileHandler
    */
   public function __construct() {
   }

   public function __destruct() {
      clearstatcache(TRUE);
   }

   public function __clone() {
   	$this->file_pointer = clone $this->getAttr_file_pointer();
   }

   /**
    * @return FileHandler
    */
   public static function getInstance() : FileHandler {
      return new FileHandler();
   }

   // Getter and setter functions.
   protected function setAttr_file_pointer(&$p_filePointer) : void {
      $this->file_pointer = $p_filePointer;
   }

   public function & getAttr_file_pointer() {
      return $this->file_pointer;
   }
   
   // Service methods

   /**
    * This method avoids getting a false result like getting false
    * result on eg. file_exists even though the file actually exists in the file-system.
    */
   protected static function getPrepairedFilename($p_filename) {
      // trim path
      $fileDirectory = trim(dirname($p_filename));
      // Normalize path-separators.
      $fileDirectory = str_replace(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $fileDirectory) .DIRECTORY_SEPARATOR;
      // trim file name
      $fileName = trim(basename($p_filename));
      // Return the Rebuild filename with path.
      return $fileDirectory."{$fileName}";
   }

   /**
    * Checks whether or not the given file in the file-system of the server is a regular-file.
    *
    * @param string $p_filename
    * @return bool Returns TRUE if the given file is a regular-file otherwise FALSE.
    */
   public static function isRegularFile(string $p_filename) : bool {
      return is_file(self::getPrepairedFilename($p_filename));
   }

   /**
    * Checks whether or not the given file in the file-system of the server is a directory.
    *
    * @param string $p_filename
    * @return bool Returns TRUE if the given file is a directory otherwise FALSE.
    */
   public static function isDirectory(string $p_filename) : bool {
      return is_dir($p_filename);
   }

   /**
    * Checks whether or not the given file in the file-system of the server is a symbolic-link.
    *
    * @param string $p_filename
    * @return bool Returns TRUE if the given file is a symbolic-link otherwise FALSE.
    */
   public static function isSymbolicLink(string $p_filename) : bool {
      return is_link($p_filename);
   }

   /**
    * Tells whether a file exists and is readable.
    * 
    * @param string $p_filename
    * @return bool
    */
   public static function isReadable(string $p_filename) : bool {
      return is_readable($p_filename);
   }

   /**
    * Tells whether the file is writeable.
    *
    * @param string $p_file
    * @return bool
    */
   public static function isWriteable(string $p_filename) : bool {
      return is_writable($p_filename);
   }

   /**
    * Tells whether the filename is executable.
    *
    * @param string $p_file
    * @return bool
    */
   public static function isExecuteable(string $p_filename) : bool {
      return is_executable($p_filename);
   }

   /**
    * Checks whether a file or directory exists in the file-system of the server.
    *
    * @param string $p_filename
    * @return bool Returns TRUE if the given file exists otherwise FALSE.
    */
   public static function doesFileExists(string $p_filename ='') : bool {
      if (empty($p_filename)) {
        return FALSE;
      } else {
        // Check if the file is in the file-system of the server.
        return self::isRegularFile($p_filename);
      }
   }

   /**
    * @param string $p_filename
    * @return string
    */
   public static function getPart_fileName($p_filename) {
   	return basename($p_filename);
   }

   /**
    * @param string $p_filename
    * @return string
    */
   public static function getPart_directoryName($p_filename) {
      // eg. dirname("/etc/passwd"); returns /etc
      return dirname($p_filename);
   }

   /**
    * Retrives the target of a symbolic-link - where the link points at.
    *
    * @param string $p_symbolicLink
    * @return string|boolean
    */
   public static function getLinkTarget($p_symbolicLink) {
      return readlink($p_symbolicLink);
   }

   public static function getFileSize($p_filename, $p_inUnits = self::UNIT_BYTES) {
      return ($p_inUnits == self::UNIT_BYTES) ? filesize($p_filename) : self::getConvertedFileSize(filesize($p_filename), $p_inUnits);
   }

   /**
    * @param int $p_bytes
    * @param string $p_units Can be 'B','KB','MB','GB' or 'TB'.
    * @return float
    */
   public static function getConvertedFileSize(int $p_bytes, string $p_units =self::UNIT_KILOBYTES) : float {
      $bytes = floatval($p_bytes);
      switch ($p_units) {
       case self::UNIT_BYTES :
         return $bytes;
         break;
       case self::UNIT_KILOBYTES :
         return $bytes/1024;
         break;
       case self::UNIT_MEGABYTES :
         return $bytes/(pow(1024, 2));
         break;
       case self::UNIT_GIGABYTES :
         return $bytes/(pow(1024, 3));
         break;
       case self::UNIT_TERABYTES :
         return $bytes/(pow(1024, 4));
         break;
       default:
         return $bytes;
         break;
      }
   }

   /**
    * Does filename tidying to get rid of unwanted chars in a possible new filename.
    * 
    * @param string $p_filename
    * @return string
    */
   public static function tidyFilename(string $p_filename) : string {
      /*
       * This allows letters a-z, digits, space (\\040), hyphen (\\-), underscore (\\_) and backslash (\\\\).
       * Everything else is removed from the string.
       */
   	$allowedPattern = "/[^a-z0-9\\040\\.\\-\\_\\\\]/i";
   	return preg_replace($allowedPattern, '', $p_filename);
   }

   /**
    * @param string $p_filename
    * @param string $p_fileContentToAdd Default blank.
    * @return void
    */
   public function appendToFile(string $p_filename, string $p_fileContentToAdd ='') : void {
      if (self::doesFileExists($p_filename)) {
         try {
            file_put_contents($p_filename, $p_fileContentToAdd, FILE_APPEND);
         } catch (Exception $e) {
            echo $e->getMessage();
         }
      } else {
         // Open for append reading and writing.
         $fileMode = 'a+';
         try {
            $fp = fopen($p_filename, $fileMode);
            if ($fp !== FALSE) {
              $bytesWritten = fwrite($fp, $p_fileContentToAdd);
              if ($bytesWritten === false) {
                trigger_error(sprintf('Unable to write to file %s', $p_filename), E_USER_ERROR);
                exit(1);
              }
              fclose($fp);
            }
         } catch (Exception $e) {
            echo $e->getMessage();
         }
      }
   }

   /**
    * Opens a given file or URL.
    *
    * @param string $p_filename
    * @param string $p_mode Default 'a+' Open file for reading and writing.
    *
    * Returns a file-pointer resource on success, or FALSE on error.
    */
   public function openFile(string $p_filename, string $p_mode ='a+') {
      try {
        $this->setAttr_file_pointer(fopen($p_filename, $p_mode));
        return $this->getAttr_file_pointer();
      } catch (Exception $e) {
        echo $e->getMessage();
        exit(2);
      }
   }

   /**
    * Closes an open file-pointer.
    * @return bool
    */
    public function closeFile() : bool {
      return fclose($this->getAttr_file_pointer());
   }

   /**
    * Returns the contents of the file as a string.
    *
    * @param string $p_filename
    * @return string
    * @throws Exception
    */
   public function getFileContent(string $p_filename) : string {
      try {
         return file_get_contents($p_filename);
      } catch (Exception $e) {
         // Re-throw exception.
         throw new Exception($e->getMessage(), $e->getCode());
         exit(1);
      }
   }

   /**
    * Deletes an existing regular file.
    *
    * @param string $p_file
    * @return void
    * @throws Exception 
    */
   public static function deleteFile(string $p_file) : void {
      try {
         if (!self::doesFileExists($p_file)) {
           throw new Exception(sprintf('The given file to delete did NOT exist (%s) ...', $p_file));
         } else {
            if (self::isRegularFile($p_file)) {
              unlink($p_file);
              clearstatcache(TRUE);
            }
         }
      } catch (Exception $e) {
         // Re-throws the exception.
         throw new Exception($e->getMessage());
      }
   }
} // End class