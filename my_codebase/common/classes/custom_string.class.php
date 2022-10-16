<?php
namespace Common\Classes;

/**
 * Filename     : custom_string.class.php
 * Language     : PHP v7.4, v7.1, v5.x
 * Date created : 30/09-2013, Ivan
 * Last modified: 02/09-2016, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * Description
 *  My string-wrapper class for handling strings in a smart and a multi-byte safe manner.
 *   
 *  @example 1:
 *   $strObj = CustomString::getInstance('Hello world ;-)', 'UTF-8');
 *   $strObj->addToContent(' Cool!');
 *
 *   echo $strObj->getUppercase(); // Displays: HELLO WORLD :-) COOL!
 *   echo $strObj->getLowercase(); // Displays: hello world :-) cool!
 *   echo $strObj->getAttr_str();  // Displays: Hello world ;-) Cool!
 *
 *   // Modify to all-uppercase letters.
 *   $strObj->toUppercase();
 *   $searchStr = 'WORLD';
 *   if ($strObj->doesOccur($searchStr)) {
 *     // Yes, there was at least one!
 *     echo sprintf("There was at least one occurrence of the string '%s'.", $searchStr);
 *   } else {
 *     echo sprintf("No there was no occurrence of the string '%s'.", $searchStr);
 *   }
 * 
 *  @example 2:
 *   $strObj_columnsList = CustomString::getInstance("USR_NAME,USR_PASSWD,USR_EMAIL", 'UTF-8');
 *   $arrFields = $strObj_columnsList->getSubStrings(',');
 */
class CustomString
{
  // Attributes
  private $str;
  private $str_encoding;

  /**
   * Default constructor.
   *
   * @param string $p_strInitialValue Default blank.
   * @param string $p_strEncoding Default 'UTF-8'
   * 
   * @return CustomString
   */  
  public function __construct($p_strInitialValue ='', $p_strEncoding ='UTF-8') {
    // Set string-encoding before setting the actual value.
    $this->setAttr_str_encoding($p_strEncoding);
    $this->setAttr_str($p_strInitialValue);
  }

  public function __destruct() {
  }

  public function __clone() {
  	 /* 
  	  * There is no reference-variable in the class,
  	  * So there is no need to force the creation of copies of those. 
  	  */
  	 // $this->object = clone $this->object;
  }

  /**
   * @return string
   */
  public function __toString() : string {
  	 return sprintf("%s : %s", $this->getAttr_str_encoding(), $this->getAttr_str());
  }

  /**
   * Creates a new instance of the class.
   * 
   * @param string $p_strEncoding
   * @param string $p_strInitialValue Default blank.
   *
   * @return CustomString
   */
  public static function getInstance($p_strInitialValue ='', $p_strEncoding ='UTF-8') : CustomString {
  	 return new CustomString($p_strInitialValue, $p_strEncoding);
  }

  // Setter and getter methods.

  /**
   * Sets the encoding of the string of the instance.
   * @param string $p_strEncoding Default is 'UTF-8'.
   */
  protected function setAttr_str_encoding(string $p_strEncoding ='UTF-8') : void {
  	 // Make sure that only supported encoding can be used.
  	 if (!self::isEncodingSupported($p_strEncoding)) {
       trigger_error("Specified character-encoding ('". $p_strEncoding ."') is NOT supported ...", E_USER_ERROR);
  	 } else {
  	   $this->str_encoding = (string) $p_strEncoding;
  	 }
  }

  /**
   * Returns the encoding of the string of the instance.
   * @return string
   */
  public function getAttr_str_encoding() : string {
  	 return $this->str_encoding;
  }
 
  /**
   * @param string $p_strValue Default value is blank.
   */
  public function setAttr_str(string $p_strValue ='') : void {
     if ($this->hasValidEncoding($p_strValue)) {
       $this->str = (string) $p_strValue;
  	 } else {
       $this->str = (string) mb_convert_encoding($p_strValue, $this->getAttr_str_encoding());
  	 }
  }

  /**
   * @return string
   */
  public function getAttr_str() : string {
  	 return $this->str;
  }

  // Service methods

  public function clear() : void {
     $this->setAttr_str('');
  }

  /**
   * @param string $p_str Default blank.
   */
  public function addToContent($p_str ='') : void {
     $strObj_addingPart = self::getInstance($this->getAttr_str_encoding(), $p_str);
     $this->toConcatenatedString($strObj_addingPart);
  }

  /**
   * Check if the string is valid for the specified encoding.
   * 
   * @param string $p_str
   * @return bool
   */
  protected function hasValidEncoding(string $p_str) : bool {
  	 return mb_check_encoding($p_str, $this->getAttr_str_encoding());
  }

  /**
   * Gets the length of the multi-byte string counted in number of characters.
   *
   * @param string $p_str
   * @return int Returns the number of characters in the given string.
   */
  public function getStringLength($p_str) : int {
  	 return mb_strlen($p_str, $this->getAttr_str_encoding());
  }

  /**
   * @return int
   */
  public function getStringLengthOfInstance() : int {
    return mb_strlen($this->getAttr_str(), $this->getAttr_str_encoding());
  }

  /**
   * Checks to see if the string of instace is blank.
   *
   * @param boolean $p_doTrim Default TRUE.
   * @return bool TRUE if its blank otherwise FALSE.
   */
  public function isBlank($p_doTrim =TRUE) : bool {
     if ($p_doTrim) {
       return (self::getTrimedWhitespaces($this->getAttr_str()) == '');
     } else {
       return ($this->getAttr_str() == '');
     }
  }

  /**
   * Checks if there are any occurences of a given string within the string of the instance.
   * 
   * @param string $p_searchStr
   * @return int|boolean Returns position of the first occurence, otherwise boolean FALSE.
   */
  public function doesOccur($p_searchStr) {
     return $this->getPosition_firstOccurrence($p_searchStr);
  }

  /**
   * Locates where the first occurrence of the given string is at.
   *  
   * @param string $p_searchStr
   * @param int $p_startOffset Default is zero.
   * 
   * @return int|boolean Returns boolean FALSE if there was not found any occurrence of the search-string otherwish it will return the character's position.
   */
  public function getPosition_firstOccurrence(string $p_searchStr, int $p_startOffset =0) {
  	 /*
  	  * Performs a multi-byte safe strpos() operation based on number of characters.
  	  * The first character's position is 0, the second character position is 1, and so on.
  	  */
  	 return mb_strpos($this->getAttr_str(), $p_searchStr, $p_startOffset, $this->getAttr_str_encoding()); 
  }

  /**
   * Locates where the last occurrence of the given string is at.
   *
   * @param string $p_searchStr
   * @param int $p_startOffset Default is zero.
   *
   * @return int|boolean Returns boolean FALSE if there was not found any occurrence of the search-string otherwish it will return the character's position.
   */
  public function getPosition_lastOccurrence(string $p_searchStr, int $p_startOffset =0) {
  	/*
  	 * Performs a multi-byte safe strrpos() operation based on number of characters.
    * The first character's position is 0, the second character position is 1, and so on.
  	 */
  	 return mb_strrpos($this->getAttr_str(), $p_searchStr, $p_startOffset, $this->getAttr_str_encoding()); 
  }

  /**
   * Returns a sub-string of the given string.
   *
   * @param string $p_str
   * @param int $p_startPos Default 0.
   * @param int $p_length Default 0.
   *
   * @return string
   */
  public function getSubString(string $p_str, int $p_startPos =0, int $p_length =0) : string {
  	 if ($p_length == 0) {
      // Default to the length of the string.
      $p_length = $this->getStringLength($p_str);
  	 }

  	 return mb_substr($p_str, $p_startPos, $p_length, $this->getAttr_str_encoding());
  }

  public function getSubString_truncatedAtLength($p_length =0) : string {
    $str = $this->getAttr_str();
    return mb_strcut($str, 0, $p_length, $this->getAttr_str_encoding());
  }

  /**
   * @return string
   */
  private static function getUppercaseOfStringObj(CustomString $p_stringObj) {
     return mb_strtoupper($p_stringObj->getAttr_str(), $p_stringObj->getAttr_str_encoding());
  }

  /**
   * @return string
   */
  public function getUppercase() : string {
     return self::getUppercaseOfStringObj($this);
  }
 
  /**
   * Modifies the string of the instance to all UPPERCASE.
   * @return void
   */
  public function toUppercase() : void {
  	 if (!$this->isBlank()) { 
  	   $this->setAttr_str(self::getUppercaseOfStringObj($this));
  	 }
  }

  /**
   * Returns the UPPERCASE version of the given string.
   *
   * @param string $p_str
   * @return string
   */
  public function getUppercaseOfStr($p_str) : string {
  	 if ($this->hasValidEncoding($p_str)) {
       return mb_strtoupper($p_str, $this->getAttr_str_encoding());
  	 } else {
       trigger_error(__METHOD__ .': Was not able to encode string due to invalid combination of string and encoding ...', E_USER_NOTICE);
       return $p_str;
  	 }
  }

  /**
   * Makes the first char of the string an UPPERCASE-char.
   * @return void
   */
  public function toUppercase_firstChar() : void {
     if (!$this->isBlank()) {
       $firstChar = $this->getUppercaseOfStr($this->getSubString($this->getAttr_str(), 0, 1));

       if ($this->getStringLength($this->getAttr_str()) >= 2) {
         $strWithoutFirstChar = $this->getLowercase($this->getSubString($this->getAttr_str(), 1));
         // Set the new string.
         $this->setAttr_str($firstChar . $strWithoutFirstChar);
       } else {
         // Else the string must have only a single-char.
       	 $this->setAttr_str($firstChar);
       }
     }
  }

  /**
   * @return string
   */
  private static function getLowercaseOfStringObj(CustomString $p_stringObj) : string {
     return mb_strtolower($p_stringObj->getAttr_str(), $p_stringObj->getAttr_str_encoding());
  }

  /**
   * @return string
   */
  public function getLowercase() : string {
     return self::getLowercaseOfStringObj($this);
  }
 
  /**
   * Modifyes the string of the instance to all lowercase.
   * @return void
   */
  public function toLowercase() : void {
  	 if (!$this->isBlank()) { 
  	   $this->setAttr_str(self::getLowercaseOfStringObj($this));
  	 }
  }

  /**
   * Returns the lowercase version of the string.
   *
   * @param string $p_str
   * @return string
   */
  public function getLowercaseOfStr(string $p_str) : string {
     if ($this->hasValidEncoding($p_str)) {
       return mb_strtolower($p_str, $this->getAttr_str_encoding());
     } else {
  	   return mb_strtolower(mb_convert_encoding($p_str, $this->getAttr_str_encoding()), $this->getAttr_str_encoding());
  	 }
  }

  /**
   * Makes sure that unwanted whitespaces are trimmed away.
   * @return void
   */
  public function toTrimmedWhitespaces() : void {
  	 $this->setAttr_str(self::getTrimedWhitespaces($this->getAttr_str()));
  }

  /**
   * Returns a trimmed string where whitespaces - both front and trailing blanks are removed.
   *
   * @param string $p_str
   * @return string
   */
  public static function getTrimedWhitespaces(string $p_str) : string {
     return trim($p_str);
  }

  /**
   * Returns a trimmed/truncated version of the string of the instance.
   *
   * @param int $p_startOffset The start-position offset. Default zero. 
   * @param int $p_trimWidth Default is a with of 20 charaters.
   * 
   * @return string
   */
  public function getTrimmedContent_fixedWidth(int $p_startOffset =0, int $p_trimWidth =20) : string {
  	 return mb_strimwidth($this->getAttr_str(), $p_startOffset, $p_trimWidth, ' ...', $this->getAttr_str_encoding());
  }

  /**
   * @return string
   */
  public function getCharAtPos(int $p_startOffset =0) : string {
    return mb_strimwidth($this->getAttr_str(), $p_startOffset, 1, '', $this->getAttr_str_encoding());
  }

  /**
   * Does string-replacement of sub-strings.
   *
   * @param string $p_searchStr
   * @param string $p_replaceStr
   * @return void
   */
  public function doReplacement(string $p_searchStr, string $p_replaceStr) : void {
  	 $this->setAttr_str(str_replace($p_searchStr, $p_replaceStr, $this->getAttr_str()));
  }

  /**
   * This function splits a multi-byte-string into an array of strings.
   *
   * @param string $p_splitDelimitor
   * @return array[] = string
   */
  public function getSplitResult($p_splitDelimitor =',') {
  	 $splitPattern = sprintf("\%s", $p_splitDelimitor);
  	 return mb_split($splitPattern, $this->getAttr_str());
  }

  /**
   * Returns a list of sub-strings.
   *
   * @param string $p_splitDelimitor
   * @return multi-type: CustomString | array[] = CustomString
   */
  public function getSubStrings($p_splitDelimitor =',') {
  	 $arrStr = $this->getSplitResult($p_splitDelimitor);
   	 if (is_array($arrStr)) {
       $arrSubStrings = array();
       foreach ($arrStr as $curStrPart) {
          $arrSubStrings[] = self::getInstance($this->getAttr_str_encoding(), $curStrPart);
       } // Each sub-string

       return $arrSubStrings;
  	 } else {
       return $this;
  	 }
  }

  /**
   * Concatenates two strings in to one single string.
   * 
   * @param string $p_str1 Default blank.
   * @param string $p_str2 Default blank.
   * 
   * @return string
   */
  private static function concatenateStrings(string $p_str1 ='', string $p_str2 ='') : string {
     return $p_str1 . $p_str2;
  }

  /**
   * Concatenates the string-content of the given String-instance with the current instance.
   * @param CustomString $p_stringObj
   * @return void
   */
  public function toConcatenatedString(CustomString $p_stringObj) : void {
  	 if ($p_stringObj->getAttr_str_encoding() != $this->getAttr_str_encoding()) {
       // The two string-encodings are not a like so we have to do a convert-process first.
       $strToConcat = $p_stringObj->getConvertedString($this->getAttr_str_encoding(), $p_stringObj);
  	 } else {
       // The two string-encodings are alike so lets just get the string-content.
       $strToConcat = $p_stringObj->getAttr_str();
  	 }

  	 // Do the actual concatenation of the two strings.
  	 $this->setAttr_str(self::concatenateStrings($this->getAttr_str(), $strToConcat));
  }

  /**
   * Converts character-encoding of the given string from an existing encoding to the wanted encoding.
   * 
   * @param string $p_toEncoding
   * @param CustomString $p_stringObj
   * @return string
   */
  public function getConvertedString(string $p_toEncoding, CustomString $p_stringObj) : string {
  	 return mb_convert_encoding($p_stringObj->getAttr_str(), $p_toEncoding, $p_stringObj->getAttr_str_encoding());
  }

  /**
   * Converts the string of the instance to the specifyed character-encoding.
   * 
   * @param string $p_wantedEncoding Valid character-encoding.
   * @return void
   */
  public function toConvertedEncoding(string $p_wantedEncoding) : void {
     $this->setAttr_str($this->getConvertedString($p_wantedEncoding, $this));
     $this->setAttr_str_encoding($p_wantedEncoding);
  }

  /**
   * Gets the list of supported string-encodings.
   * @return array
   */
  public static function getSupportedEncodings() {
     if (function_exists('mb_list_encodings')) {
       return mb_list_encodings();
     } else {
       trigger_error('Please make your SysAdmin enable the needed support for multi-byte strings in PHP!', E_USER_ERROR);
       exit(1);
     }
  }

  /**
   * Checks if the given encoding is supported or not.
   * 
   * @param string $p_strEncoding
   * @return bool Returns boolean TRUE if the encoding is supported otherwise FALSE.
   */
  public static function isEncodingSupported(string $p_strEncoding) : bool {
  	  $arrSupportedEncodings = self::getSupportedEncodings();
  	  if (!is_array($arrSupportedEncodings)) {
       trigger_error('Sorry, there was none supported string-encodings ...', E_USER_ERROR);
       return FALSE;
  	  } else {
       // Checks if the value exists in the array 
       return in_array($p_strEncoding, $arrSupportedEncodings);
     }
  }
} // End class