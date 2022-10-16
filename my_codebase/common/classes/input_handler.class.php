<?php
namespace Common\Classes;

/**
 * Script-name  : input_handler.class.php 
 * Language     : PHP v7.4, v7.2
 * Date created : IMA, 06/10-2009
 * Last modified: IMA, 07/10-2022
 * Developers   : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 * 
 * @copyright: Copyright (C) 2009, 2011 by Ivan Mark Andersen
 *
 * Description
 *  The purpose of this class is to handle the retrive of input variables to starter-scripts in a safe, smart and easy way.
 */
class InputHandler
{
   const DEFAULT_DATATYPE = 'string';
   /**
    * @var string
    */
   protected $dataType;

   /**
    * Default constructor
    * @param string|boolean $p_dataTypeIdent Default is FALSE.
    */
   public function __construct($p_dataTypeIdent =false) {
      $this->setAttr_dataType($p_dataTypeIdent);
   }

   public function __destruct() {
   }

   /**
    * @return InputHandler
    */
   public static function getInstance() : InputHandler {
      return new InputHandler();
   }

   private function setAttr_dataType(string $p_dataTypeIdent =self::DEFAULT_DATATYPE) {
      // Set to what ever given type.
      $this->dataType = $p_dataTypeIdent;
   }

   /**
    * @return string
    */
   public function getAttr_dataType() : string {
      return $this->dataType;
   }

   /**
    * Returns a boolean result on wheter or not the given variable is of a boolean data-type.
    *
    * @param mixed $p_var Given variable to check.
    * @return boolean Returns TRUE if the variable is a boolean, otherwise FALSE.
    */
   public function isBoolean($p_var) : bool {
      return ($p_var == 'true' || $p_var == 'false');
   }

   /**
    * Returns a boolean result on wheter or not the given variable is of a string data-type.
    *
    * @param mixed $p_var Given variable to check.
    * @return boolean Returns TRUE if the value of variable is a string otherwise FALSE.
    */
   public function isString($p_var) : bool {
      return is_string($p_var);
   }

   /**
    * Returns a boolean result on wheter or not the given variable is of an integer data-type.
    *
    * @param mixed $p_var Given variable to check.
    * @return boolean 
    */
   public function isInteger($p_var) : bool {
      return (preg_match('/^-?[0-9]+$/', $p_var));
   }

   /**
    * Returns a boolean result on wheter or not the given variable is of an integer data-type and value of the variable is positive.
    * 
    * @param mixed $p_var
    * @return bool
    */
   public function isPosInteger($p_var) : bool {
      return (preg_match('/^[1-9]+([0-9]*)?$/', $p_var));
   }

   /**
    * Returns a boolean result on wheter or not the given variable is of a possible floating-point data-type.
    *
    * @param mixed $p_var Given variable to check.
    * @return boolean Returns TRUE if the value of the variable is a float, otherwise FALSE.
    */
   public function isFloat($p_var) : bool {
      $regularExp_float = '/^\d*(?:\.\d+)?$/';
      return preg_match($regularExp_float, $p_var);
   }

   /**
    * Returns a boolean result on whether or not the given variable is of an array data-type.
    *
    * @param mixed $p_var Given variable to check.
    * @return bool Returns TRUE if the value of the variable is an array, otherwise FALSE.
    */
   public function isArray($p_var) : bool {
      return is_array($p_var);
   }

   /**
    * Checks wheter or not the value of the given variable is an e-mail.
    *
    * @param mixed $p_var Given variable to check.
    * @return boolean Returns TRUE if the value of the given variable match the syntax of an e-mail, otherwise FALSE.
    */
   public function isEmail($p_var) : bool {
      // Setup the regular-expression for an e-mail.
      $regularExp_email = "/^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";
      return preg_match($regularExp_email, $p_var);
   }

   /**
    * Checks if the given value is a valid UUID or NOT?
    * According to RFC 4122, a valid UUID has 32 characters under five sections, where a dash character separates each.
    * The first section has eight characters, the second, the third, the fourth has 4, and the last section 12.
    * UUIDs are written in hexadecimal, and therefore, each digit can be from 0 to 9 or a letter from a to f.
    */
   public static function isValidUUID(string $p_uuid) : bool {
      $regularExp_uuid = "/^[0-9a-f]{8}-[0-9a-f]{4}-[0-5][0-9a-f]{3}-[089ab][0-9a-f]{3}-[0-9a-f]{12}$/i";
      return preg_match($regularExp_uuid, $p_uuid);
   }

   /**
    * Checks for a valid internet URL
    *
    * @param string $p_url The value to check
    * @return boolean TRUE if the value is a valid URL, FALSE if not
    */
   public static function isInternetURL($p_url) : bool {
   	if (preg_match("/^http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/i", $p_url)) {
        return TRUE;
   	} else {
        return FALSE;
   	}
   }

   /**
    * Returns a boolean result on wheter or not the value of the given variable match the specifyed data-type.
    *
    * @param mixed $p_var Given variable.
    * @return bool Return TRUE if the value match the data-type, otherwise FALSE.
    */
   private function isDataTypeViolated($p_var) : bool {
      $dataType = $this->getAttr_dataType();
      switch ($dataType) {
         case 'boolean' : {
            $isTypeViolated = !$this->isBoolean($p_var);
            break;
         }

         case 'str' :
         case 'string' :
         case 'text' : {
            $isTypeViolated = !$this->isString($p_var);
            break;
         }
         case 'integer' : {
            $isTypeViolated = !$this->isInteger($p_var);
            break;
         }
         case 'pos_int' : {
            $isTypeViolated = !$this->isPosInteger($p_var);
            break;
         }
         case 'float' : {
            $isTypeViolated = !$this->isFloat($p_var);
            break;         
         }
         case 'email' : {
            $isTypeViolated = !$this->isEmail(trim($p_var));
            break;
         }
         case 'uuid' : {
            $isTypeViolated = !self::isValidUUID(trim($p_var));
            break;
         }
         case 'url' : {
            $isTypeViolated = !self::isInternetURL(trim($p_var));
            break;
         }
         case 'array' : {
            $isTypeViolated = !$this->isArray($p_var);
            break;
         }
         default : {
            // Trigger user-error for unhandled data-type.
            trigger_error("Data-type: $dataType is unknown and is NOT handled by the InputHandler object ...", E_USER_ERROR);

            // Set default.
            $isTypeViolated = (boolean) false;
            break;
         }
      }

      return $isTypeViolated;
   }

   /**
    * Validates wheter or not the value of the variable is valid.
    *
    * @param mixed $p_var The given variable to validate.
    * @param resource $pbr_dataValue Type-casted value if valid, otherwise the value as given.
    * 
    * @return boolean Returns TRUE if the value of the variable is valid, otherwise FALSE.
    */
   private function validateValue($p_var, &$pbr_dataValue) {      
      if (!$this->isDataTypeViolated($p_var)) {
        $isValid = (boolean) true;
        $dataType = $this->getAttr_dataType();

        switch ($dataType) {
         case 'boolean' : {
            $pbr_dataValue = (boolean) ($p_var == 'true')?true:false;
            break;
         }
         case 'str' :
         case 'string' :
         case 'text' : {
            $pbr_dataValue = (string) $p_var;
            break;
         }
         case 'integer' :
         case 'pos_int' : {
            $pbr_dataValue = (int) $p_var;
            break;
         }
         case 'float' : {
            $pbr_dataValue = (float) $p_var;
            break;         
         }
         case 'email' : {
            $pbr_dataValue = strtolower(trim($p_var));
            break;
         }
         case 'uuid' : {
            $pbr_dataValue = (string) $p_var;
            break;
         }
         case 'url' : {
            $pbr_dataValue = (string) $p_var;
            break;
         }
         case 'array' : {
            $pbr_dataValue = $p_var;
            break;
         }
         default : {
            $pbr_dataValue = (string) $p_var;            
            break;
         }
       }
      } else {
        // Data-type was violated value is NOT valid!
        $isValid = (boolean) false;
        $pbr_dataValue = $p_var;
      }

      return $isValid; 
   }

   /**
    * Retrives input values from the super-global _POST array.
    *
    * @param string $p_varName
    * @param string $p_dataType
    * 
    * @return array Single-dim assoc. array Eg.: 'is_set' => boolean, 'is_empty' => boolean, 'is_valid' => boolean, 'value' => mixed
    */
   public function retriveVarFrom_POST($p_varName, $p_dataType) {
      $this->setAttr_dataType($p_dataType);

      // First check, if the input-value exists.
      $hasRequestedInput = filter_has_var(INPUT_POST, $p_varName);
      if ($hasRequestedInput) {
        $isEmpty = empty($_POST[$p_varName]);
        $isValid = $this->validateValue($_POST[$p_varName], $pbr_value);

        return array('is_set' => $hasRequestedInput, 'is_empty' => $isEmpty, 'is_valid' => $isValid, 'value' => $pbr_value);
      } else {
        $isEmpty = (boolean) false;
        $isValid = (boolean) false;

        return array('is_set' => $hasRequestedInput, 'is_empty' => $isEmpty, 'is_valid' => $isValid, 'value' => null);
      }
   }

   /**
    * Retrieves user input-values from the super-global _GET array.
    *
    * @param string $p_varName
    * @param string $p_dataType
    *
    * @return array Single-dim assoc. array Eg.: 'is_set' => boolean, 'is_empty' => boolean, 'is_valid' => boolean, 'value' => mixed
    */
   public function retriveVarFrom_GET($p_varName, $p_dataType) {
      $this->setAttr_dataType($p_dataType);

      // First check, if the input-value exists.
      $hasRequestedInput = $this->hasRequestedInput_inGET($p_varName);
      if ($hasRequestedInput) {
        $isEmpty = empty($_GET[$p_varName]);
        $isValid = $this->validateValue($_GET[$p_varName], $pbr_value);

        return array('is_set' => $hasRequestedInput, 'is_empty' => $isEmpty, 'is_valid' => $isValid, 'value' => $pbr_value);
      } else {
        $isEmpty = (boolean) false;
        $isValid = (boolean) false;

        return array('is_set' => $hasRequestedInput, 'is_empty' => $isEmpty, 'is_valid' => $isValid, 'value' => null);
      }
   }

   /**
    * @param string $p_keyName
    * @return bool
    */
   public function hasRequestedInput_inGET(string $p_keyName) : bool {
      return filter_has_var(INPUT_GET, $p_keyName);
   }
} // End class