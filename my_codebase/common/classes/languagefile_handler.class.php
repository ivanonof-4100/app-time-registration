<?php
namespace Common\Classes;

use Exception;
use Common\Classes\FileHandler;

/**
 * Filename  : languagefile_handler.class.php
 * Language     : PHP v7.4
 * Date created : 10/10-2009, Ivan
 * Last modified: 16/04-2023, Ivan
 * Author(s)    : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright: Copyright (C) 2023 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * Wraps handling of language-files.
 *
 * @example:
 * $LanguagefileHandler = LanguagefileHandler::getInstance(FALSE, 'da', 'utf8');
 * // Loading a language-file: user_profile.lang.da-utf8.php
 * $LanguagefileHandler->loadLanguageFile('user_profile');
*/
class LanguagefileHandler extends FileHandler
{
  const DEFAULT_CHARSET = 'utf8';
  const DEFAULT_LANGUAGE = 'da';

  /**
   * @var bool
   */
  protected $useAutoDetect;

  /**
   * @var string ISO 639-1 (2-char language-code).
   */
   protected $languageIdent;

   /**
    * @var string Default 'utf8'
    */
   protected $codepageIdent;
   private $arrLang;

   /**
    * @param bool $p_autoDetect Default FALSE.
    * @param string $p_languageIdent Default 'da'
    * @param string $p_codepageCharset Default 'utf8'
    */
   public function __construct(bool $p_autoDetect =FALSE,
                               string $p_languageIdent =self::DEFAULT_LANGUAGE,
                               string $p_codepageCharset =self::DEFAULT_CHARSET) {
      parent::__construct();
      $this->useAutoDetect = $p_autoDetect;

      if ($this->useAutoDetect) {
        $this->setLanguageIdent($this->getDetectedLanguageOfBrowser());
      } else {
        $this->setLanguageIdent($p_languageIdent);
      }

      $this->setCodepageIdent($p_codepageCharset);
      $this->arrLang = array();
   }

   public function __destruct() {
      parent::__destruct();
   }

   /**
    * @param bool $p_autoDetect Default FALSE.
    * @param string $p_languageIdent Default 'da'
    * @param string $p_codepageCharset Default 'utf8'
    * @return LanguagefileHandler
    */
   public static function getInstance(bool $p_autoDetect =FALSE,
                                      string $p_languageIdent =self::DEFAULT_LANGUAGE,
                                      string $p_codepageCharset =self::DEFAULT_CHARSET) : LanguagefileHandler {
      return new LanguagefileHandler($p_autoDetect, $p_languageIdent, $p_codepageCharset);
   }
  
   /**
    * Sets the language code according to ISO 639-1 for the Danish language is "da".
    * @param string $p_languageIdent Default 'da'.
    */
   private function setLanguageIdent(string $p_languageIdent = self::DEFAULT_LANGUAGE) {
      $this->languageIdent = (string) $p_languageIdent;
   }

   /**
    * Returns the ISO-639 language-ident of the instance.
    * @return string
    */
   public function getLanguageIdent() {
      return $this->languageIdent;
   }

   private function setCodepageIdent($p_codepageIdent =false) {
      if ($p_codepageIdent) {
        $this->codepageIdent = trim(str_replace('-', '', strtolower($p_codepageIdent)));
      } else {
        $this->codepageIdent = trim(str_replace('-', '', strtolower(self::DEFAULT_CHARSET)));
      }
   }

   /**
    * Returns the lowercase iden of the codepage used, that is without any dashes.
    * @return string Eg: utf8.
    */
   public function getCodepageIdent() : string {
      return $this->codepageIdent;
   }

   /**
    * Setup the use of locales.
    * @param string|boolean $p_posixLanguageIdent.
    *
    * NOTE:
    * Make sure the right langeuage-packs is installed.
    * You can ether install the seperate language-packs you want or just install all langeuage-packs at once.
    * Debian GNU/Linux: apt-get install locales-all
    *
    * After installing the correct language-packs the your need to restart the Apache web-server.
    * The locale "da_DK.utf8" can then be used in after restarting the web-server.
    */
   private function setupLocales($p_posixLanguageIdent =false) {
      if ($p_posixLanguageIdent) {
        $posixLanguageIdent = $p_posixLanguageIdent;
      } else {
        $posixLanguageIdent = $this->getPOSIXLanguageIdent();
      }

      // Setup locals.
      $currentLocale = $posixLanguageIdent .'.'. $this->getCodepageIdent();
/*
      if (APP_DEBUG_MODE) {
        self::addDebugMessage(__METHOD__ .": Locales: Trying to setup locales ($currentLocale)", __FILE__, __LINE__);
      }
*/
      $loadedLocale = setlocale(LC_ALL, array($currentLocale, $posixLanguageIdent));
/*
      if ($loadedLocale === false) {
        if (DEBUG) {
          self::addDebugMessage(__METHOD__ .': Locales: Load failed ...', __FILE__, __LINE__);
        }
      } else {
        if (DEBUG) {
          self::addDebugMessage(__METHOD__ .": Locales: <b>$loadedLocale</b> was set as locale ...", __FILE__, __LINE__);
        }
      }
*/
   }

   /**
    * Detects the perfered locale language-ident of the web-browser.
    * @return string Return locale e.g 'es', 'en', 'de', 'da' ...
    */
   public static function getDetectedLanguageOfBrowser() {
      if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        // Detect the prefered language. 
        return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
      } elseif ($_SERVER['HTTP_USER_AGENT']) {
        // Set default
        $detectedLanguageIdent = self::DEFAULT_LANGUAGE;
        $userAgentStr = explode(';', $_SERVER['HTTP_USER_AGENT']);

        for ($i=0; $i < sizeof($userAgentStr); $i++) {
           $arrLanguages = explode('-', $userAgentStr[$i]);
           if (sizeof($arrLanguages) == 2) {
             if (strlen(trim($arrLanguages[0])) == 2) {
               $detectedLanguageIdent = trim($arrLanguages[0]);
             }
           }
        } // for-loop
        return $detectedLanguageIdent;
      } else {
        // Default something!
        return APP_LANGUAGE_IDENT;
      }
   }

   /**
    * Retrives the POSIX language-ident of the ISO-639 language-ident.
    * @return string|boolean
    */
   public function getPOSIXLanguageIdent() {
      // POSIX Language-idents.
      $arrKnownPOSIXLanguageIdents['da'] = 'da_DK'; // Danish
      $arrKnownPOSIXLanguageIdents['en'] = 'en_GB'; // English

      if (is_array($arrKnownPOSIXLanguageIdents)) {
        $currentISO639LanguageIdent = $this->getLanguageIdent();
        if (array_key_exists($currentISO639LanguageIdent, $arrKnownPOSIXLanguageIdents)) {
          return $arrKnownPOSIXLanguageIdents[$currentISO639LanguageIdent];
        }
      } else {
        return false;
      }
   }

   /**
    * Returns a full-path filename of the given language-file.
    *
    * @param string $p_languageFile
    * @param string|boolean $p_customFilePath
    * 
    * @return string
    */
   protected function getFullPathFilename($p_languageFile, $p_customFilePath =FALSE) : string {
      // Eg. filename: user_profile.lang.da-utf8.php
   	  $filename = sprintf("%s.lang.%s-%s.php", $p_languageFile, $this->getLanguageIdent(), $this->getCodepageIdent());
   	  if ($p_customFilePath) {
   	  	return $p_customFilePath . $filename;
   	  } else {
   	  	return APP_LANGUAGE_PATH . $filename;
   	  }
   }

   /**
    * Loads the specifyed language-file if that language-file exists in the filesystem of the server and the file got to be readable.
    *
    * @param string $p_pseudoLanguageFile
    * @param string|boolean $p_customFilePath Default FALSE
    * @return boolean Returns TRUE on successful file load otherwise FALSE.
    */
   public function loadLanguageFile($p_pseudoLanguageFile, $p_customFilePath =FALSE) {
      $realLanguageFilename = $this->getFullPathFilename($p_pseudoLanguageFile, $p_customFilePath);
      if (self::doesFileExists($realLanguageFilename)) {
        if (self::isRegularFile($realLanguageFilename) && self::isReadable($realLanguageFilename)) {
          // The filename is a regular-file that is readable.
          try {
            require($realLanguageFilename);
            if (isset($lang)) {
          	  $this->arrLang = array_merge($this->arrLang, $lang);
          	  $wasSuccessful = (boolean) true;
              return $wasSuccessful;
            } else {
            	$wasSuccessful = (boolean) false;
        	    trigger_error(__METHOD__ .": It was not possible to merge the language-entries of the language-file ($realLanguageFilename) with the language-entries in use ...", E_USER_WARNING);
            }
          } catch(Exception $e) {
            echo $e->getMessage();
          }
        } else {
          // The file is NOT a regular-file.
          $wasSuccessful = (boolean) false;
          trigger_error(__METHOD__ .": Language-file ($realLanguageFilename) is either NOT a regular file or is NOT readable ...", E_USER_ERROR);
        }
      } else {
        $wasSuccessful = (boolean) false;
        trigger_error(__METHOD__ .": Language-file ($realLanguageFilename) did NOT exists ...", E_USER_WARNING);
      }

      return $wasSuccessful;
   }

   /**
    * Loads multiple language-files.
    * @param string $p_langFiles Eg. "'admin_pages','custom_datetime'"
    * @param string $p_customFilePath
    * @return void
    */
   public function loadLanguageFiles(string $p_langFiles ='', string $p_customFilePath) : void {
     $arrLangFiles = explode(',', $p_langFiles);
     if (is_array($arrLangFiles)) {
       foreach ($arrLangFiles as $curLangFile) {
         try {
          $this->loadLanguageFile($curLangFile, $p_customFilePath);
         } catch (Exception $e) {
          Throw new Exception($e->getMessage());
         }
       }
     } else {
       // Load only a single language-file.
       try {
        $this->loadLanguageFile($p_langFiles, $p_customFilePath);
       } catch (Exception $e) {
        Throw new Exception($e->getMessage());
       }
     }
   }

   /**
    * Returns the formatted content of a given entry in the language-file.
    *
    * @param string $p_entryIdent Ident that identifyes an entry in the language-array.
    * @param mixed|boolean $p_param1 Default FALSE.
    * @param mixed|boolean $p_param2 Default FALSE.
    * @param mixed|boolean $p_param3 Default FALSE.
    * @param mixed|boolean $p_param4 Default FALSE.
    *
    * @return string|boolean
    */
   public function getEntryContent($p_entryIdent, $p_param1 =false, $p_param2 =false, $p_param3 =false, $p_param4 =false) {
      if (is_array($this->arrLang)) {
        if (array_key_exists($p_entryIdent, $this->arrLang)) {
          if ($p_param1 && $p_param2 && $p_param3 && $p_param4) {
            return sprintf($this->arrLang[$p_entryIdent], $p_param1, $p_param2, $p_param3, $p_param4);
          } elseif ($p_param1 && $p_param2 && $p_param3) {
            return sprintf($this->arrLang[$p_entryIdent], $p_param1, $p_param2, $p_param3);
          } elseif ($p_param1 && $p_param2) {
            return sprintf($this->arrLang[$p_entryIdent], $p_param1, $p_param2);
          } elseif ($p_param1) {
            return sprintf($this->arrLang[$p_entryIdent], $p_param1);
          } else {
            return sprintf($this->arrLang[$p_entryIdent]);
          }
        } else {
          // Not found!
          trigger_error(__METHOD__ .": Language-entry '$p_entryIdent' was not found in the loaded language-files ...", E_USER_WARNING);
          return FALSE;
        }
      } else {
        // Not an array => cant be found!
        trigger_error(__METHOD__ .": There was nothing in the language-array ...", E_USER_WARNING);
        return FALSE;
      }
   }

   /**
    * @param string $p_entryIdent
    * @return boolean
    */
   public function doesLanguageEntryExists(string $p_entryIdent) {
      return array_key_exists($p_entryIdent, $this->arrLang);
   }

   public static function formatSubElements($p_elementValue) {
      if (is_string($p_elementValue)) {
        return sprintf("'%s '", $p_elementValue);
      } else {
        return $p_elementValue;
      }
   }

   /**
    * Returns the localized and formatted content of a given entry in the language-file.
    * Each entry in the language-file can have a dynamic number of arguments and that we also handle here, there is no limit to how many arguments there can be in a language-string.
    * @return string|boolean
    */
   public function getLocalizedContent() {
      $arrArgs = func_get_args();
      $numArgs = count($arrArgs);

      if (is_array($arrArgs) && ($numArgs >= 1)) {
        if (is_array($this->arrLang)) {
          // First element is the unique entry-ident.
          $entryIdent = array_shift($arrArgs);
          $numArgs = count($arrArgs); // Re-count the number of arguments.

          if ($this->doesLanguageEntryExists($entryIdent)) {
            if ($numArgs == 0) {
              // None parameters in the string.
              return sprintf($this->arrLang[$entryIdent]);
            } else {
              $arrFormattedElements = array_map('self::formatSubElements', $arrArgs);
              // One to multiple parameters in the string.
              return sprintf($this->arrLang[$entryIdent], implode(',', $arrFormattedElements));
            }
          } else {
            // Not found!
            trigger_error(__METHOD__ .": Language-entry '$entryIdent' was not found in the loaded language-files ...", E_USER_WARNING);
            return FALSE;
          }
        } else {
          // Not an array => cant be found!
          trigger_error(__METHOD__ .': There was nothing in the language-array ...', E_USER_WARNING);
          return FALSE;
        }
      } else {
        trigger_error(__METHOD__ .': No arguments were passed to the method! There need to be at least one ...', E_USER_ERROR);
      }
   }
} // End class