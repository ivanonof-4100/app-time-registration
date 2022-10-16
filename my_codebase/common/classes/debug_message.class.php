<?php
/**
 * Script-name  : debug_message.class.php 
 * Language     : PHP v5.x
 * Date created : IMA, 19/11-2012
 * Last modified: IMA, 19/11-2012
 * Author(s)    : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2012 by Ivan Mark Andersen
 *
 * Description
 *
 *  @example
 *  
*/
class DebugMessage
{
   // Attributes
   protected $debug_text;
   protected $debug_datetime;
   protected $debug_filename;
   protected $debug_linenumber;

   // Methods

   /**
    * Default constructor of the class.
    *
    * @param string $p_debugText
    * @param string $p_debugFilename
    * @param int|boolean $p_debugLineNumber Default FALSE.
    */
   public function __construct($p_debugText, $p_debugFilename =FALSE, $p_debugLineNumber =FALSE) {
      $this->setAttr_debug_text($p_debugText);

      if ($p_debugFilename) {
        $this->setAttr_debug_filename($p_debugFilename);
      } else {
        // Use defaults.
        $this->setAttr_debug_filename();
      }

      if ($p_debugLineNumber) {
        $this->setAttr_debug_linenumber($p_debugLineNumber);
      } else {
        // Use defaults.
        $this->setAttr_debug_linenumber();
      }
/*
      $dateTimeObj = new DateTime('now', new DateTimeZone(date_default_timezone_get()));
      $this->setAttr_debug_datetime($dateTimeObj->format('Y-m-d H:i:s'));
*/
      $this->setAttr_debug_datetime(new DateTime('now', new DateTimeZone(date_default_timezone_get())));  
   } // method __construct

   public function __destruct() {
   } // method __destruct

   /**
    * Sets the debug-text attribute of the instance.
    * @param string $p_debugText
    */
   protected function setAttr_debug_text($p_debugText ='') {
      $this->debug_text = (string) $p_debugText;
   } // method setAttr_debug_text

   /**
    * @return string
    */
   public function getAttr_debug_text() {
      return $this->debug_text;
   } // method getAttr_debug_text

   /**
    * @param DateTime $p_debugTime
    */
   protected function setAttr_debug_datetime(DateTime $p_debugTime) {
      $this->debug_datetime = $p_debugTime;
   } // method setAttr_datetime

   /**
    * @return DateTime
    */
   public function getAttr_debug_datetime() {
      return $this->debug_datetime;
   } // method getAttr_debug_datetime

   /**
    * Sets the value of the attribute of the debug_filename of the instance. 
    * @param string $p_filename Default blank.
    */
   protected function setAttr_debug_filename($p_filename ='') {
     $this->debug_filename = (string) $p_filename;
   } // method setAttr_debug_filename

   /**
    * @return string
    */
   public function getAttr_debug_filename() {
      return $this->debug_filename;     
   } // method getAttr_debug_filename

   /**
    * @param int|boolean $p_linenumber Default boolean FALSE.
    */
   protected function setAttr_debug_linenumber($p_linenumber =FALSE) {
      if ($p_linenumber) {
        $this->debug_linenumber = (int) $p_linenumber;
      } else {
        $this->debug_linenumber = (boolean) false;
      }
   } // method setAttr_debug_linenumber

   /**
    * @return int|boolean
    */
   public function getAttr_debug_linenumber() {
      return $this->debug_linenumber;
   } // method getAttr_debug_linenumber
} // End class