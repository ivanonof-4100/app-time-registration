<?php
/** 
 * Filename     : handled_product.class.php
 * Language     : PHP v7+
 * Date created : 19/01-2017, Ivan
 * Last modified: 23/01-2017, Ivan
 * Developers   : @author Ivan Mark Andersen <ima@dectel.dk>
 *
 * @copyright Copyright (C) 2017 by Ivan Mark Andersen
 * 
 * Description:
 *  Model-class for handling handled-products entries.
 */
require_once(PATH_COMMON_MODEL .'saveable_object.interface.php');
require_once(PATH_COMMON_MODEL .'std_model.class.php');

class HandledProduct extends StdModel implements SaveableObjectInterface
{
  const PRODUCT_DEMO_YES = 'Y';
  const PRODUCT_DEMO_NO = 'N';

  // Attributes
  private $handling_id;
  private $handling_type;
  private $product_serial_number;
  private $product_was_demo;
  private $custom_warranty_months;
  private $product_date_sent;
  private $handling_created;
  private $handling_modifyed;
  private $user_id_created;
  private $user_id_modifyed;

  // Methods

  /**
   * Default Constructor
   * 
   * @param int|bool $p_handlingId Default FALSE.
   * @param string|bool $p_handlingType = Default FALSE.
   * @param string|bool $p_productSerialNumber = Default FALSE.
   * @param string $p_productWasDemo
   * @param string $p_productDateSent
   * @param string $p_handlingCreated
   * @param string $p_handlingModifyed
   * @param int $p_userIdCreated
   * @param int $p_userIdModifyed
   *
   * @return HandledProduct
   */
  public function __construct($p_handlingId =FALSE,
                              $p_handlingType =FALSE,
                              $p_productSerialNumber =FALSE,
                              $p_productWasDemo =FALSE,
                              $p_customWarrantyMonths = FALSE,
                              $p_productDateSent ='',
                              $p_handlingCreated =FALSE,
                              $p_handlingModifyed =FALSE,
                              $p_userIdCreated =FALSE,
                              $p_userIdModifyed =FALSE
                             )
  {
     parent::__construct();

     // handling_id
     if ($p_handlingId === FALSE) {
       $this->setAttr_handling_id();
       // Default 
     } else {
       // Use the argument as is.
       $this->setAttr_handling_id($p_handlingId);
     }

     // handling_type
     if ($p_handlingType) {
       $this->setAttr_handling_type($p_handlingType);
     } else {
       $this->setAttr_handling_type();
     }

     // product_serial_number
     if ($p_productSerialNumber) {
       $this->setAttr_product_serial_number($p_productSerialNumber);
     } else {
       $this->setAttr_product_serial_number();
     }

     // product_was_demo
     if ($p_productWasDemo) {
       $this->setAttr_product_was_demo($p_productWasDemo);
     } else {
       // Use default.
       $this->setAttr_product_was_demo();
     }

     // custom_warranty_months
     if ($p_customWarrantyMonths === FALSE) {
       $this->setAttr_custom_warranty_months();
       // Default 
     } else {
       // Use the argument as is.
       $this->setAttr_custom_warranty_months($p_customWarrantyMonths);
     }

     // product_date_sent
     if ($p_productDateSent) {
       $this->setAttr_product_date_sent($p_productDateSent);
     } else {
       $this->setAttr_product_date_sent();
     }
  } // method __construct

  /**
   * Default destructor of the class.
   */
  public function __destruct()
  {
     parent::__destruct();
  } // method __destruct

  // Getter and setter functions goes here.

  /**
   * @param int $p_handlingId Default 0.
   */
  private function setAttr_handling_id($p_handlingId =0)
  {
     $this->handling_id = (int) $p_handlingId;
  } // method setAttr_handling_id

  /**
   * @return int
   */
  public function getAttr_handling_id()
  {
     return $this->handling_id;
  } // method getAttr_handling_id

  /**
   * @return int
   */
  public function getId()
  {
     return $this->getAttr_handling_id();
  } // method getId

  /**
   * Sets the attribute of the handling_type.
   * @param int $p_handlingType.  1: 'Sold product', 2: 'Serviced product'.
   */
  public function setAttr_handling_type($p_handlingType =1)
  {
      $this->handling_type = (int) $p_handlingType;
  } // method setAttr_handling_type

  /**
   * Returns the value of the attribute of handling_type of the instance.
   * @return int
   */
  public function getAttr_handling_type()
  {
      return $this->handling_type;
  } // method getAttr_handling_type

  /**
   * Sets the serial-number of the instance.
   * @param string $p_productSerialNumber Default blank.
   */
  public function setAttr_product_serial_number($p_productSerialNumber ='')
  {
     $this->product_serial_number = (string) $p_productSerialNumber;
  } // method setAttr_product_serial_number

  /**
   * @return string
   */
  public function getAttr_product_serial_number()
  {
     return $this->product_serial_number;
  } // method getAttr_product_serial_number

  /**
   * Sets if the product was demo-attribute of the instance.
   * @param string $p_productWasDemo. 'N': No, 'Y': Yes.
   */
  public function setAttr_product_was_demo($p_productWasDemo ='N')
  {
     $this->product_was_demo = (string) $p_productWasDemo;
  } // method setAttr_product_was_demo

  /**
   * @return string
   */
  public function getAttr_product_was_demo()
  {
     return $this->product_was_demo;   
  } // method getAttr_product_was_demo

  /**
   * @return boolean
   */
  public function wasDemoProduct()
  {
     return ($this->getAttr_product_was_demo() == self::PRODUCT_DEMO_YES);
  } // method wasDemoProduct

  /**
   * @param int $p_customWarrantyMonths Default null.
   */
  public function setAttr_custom_warranty_months($p_customWarrantyMonths =null)
  {
     $this->custom_warranty_months = $p_customWarrantyMonths;
  } // method setAttr_custom_warranty_months

  /**
   * @return int|null
   */
  public function getAttr_custom_warranty_months()
  {
     return $this->custom_warranty_months;
  } // method getAttr_custom_warranty_months

  public function setAttr_product_date_sent($p_productDateSent ='')
  {
     $this->product_date_sent = (string) $p_productDateSent;
  } // method setAttr_product_date_sent

  /**
   * @return string
   */
  public function getAttr_product_date_sent($p_asDateTime =false)
  {
     if ($p_asDateTime) {
       return DateTime::createFromFormat('Y-m-d', $this->product_date_sent);
     } else {
       return $this->product_date_sent;   
     }
  } // method getAttr_product_date_sent

  // Service methods

  public function getWarrantyPeriod()
  {
     $periodStartDate = DateTime::createFromFormat('Y-m-d', $this->getAttr_product_date_sent());
     $periodEndDate = DateTime::createFromFormat('Y-m-d', $this->getAttr_product_date_sent());

     if ($this->wasDemoProduct()) {
       $customWarrantyMonths = $this->getAttr_custom_warranty_months();
       if (isset($customWarrantyMonths)) {
         $customModifyStr = sprintf('+%d months - 1 day', $customWarrantyMonths);
       } else {
         // Default to use half a year when the product was a demo-product.
         $customModifyStr = sprintf('+%d months - 1 day', 6);
       }
       $periodEndDate->modify($customModifyStr);
     } else {
       $customWarrantyMonths = $this->getAttr_custom_warranty_months();
       if (!empty($customWarrantyMonths)) {
         // Use the custom defined months for the product.
         $customModifyStr = sprintf('+%d months - 1 day', $customWarrantyMonths);
         $periodEndDate->modify($customModifyStr);
       } else {
         // Default to the 2 year warranty-period.
         $customModifyStr = sprintf('+%d years - 1 day', 2);
         $periodEndDate->modify($customModifyStr);
       }
     }

     // Set common display-format of the dates.
     $dateDisplayFormat = 'd-m-Y';
     return sprintf('%s - %s', $periodStartDate->format($dateDisplayFormat), $periodEndDate->format($dateDisplayFormat));
  } // method getWarrantyPeriod

  /**
   * @param int $p_userId
   */
  public function setAttr_user_id_created($p_userId)
  {
     $this->user_id_created = (int) $p_userId;
  } // method setAttr_user_id_created

  /**
   * @return int
   */
  public function getAttr_user_id_created()
  {
     return $this->user_id_created;  
  } // method getAttr_user_id_created

   /**
    * @return HandledProduct
    */
   public static function getInstance($p_handlingId =FALSE,
                              $p_handlingType =FALSE,
                              $p_productSerialNumber =FALSE,
                              $p_productWasDemo =FALSE,
                              $p_customWarrantyMonths =FALSE,
                              $p_productDateSent ='',
                              $p_handlingCreated =FALSE,
                              $p_handlingModifyed =FALSE,
                              $p_userIdCreated =FALSE,
                              $p_userIdModifyed =FALSE)
   {
      return new HandledProduct($p_handlingId,
                                $p_handlingType,
                                $p_productSerialNumber,
                                $p_productWasDemo,
                                $p_customWarrantyMonths,
                                $p_productDateSent,
                                $p_handlingCreated,
                                $p_handlingModifyed,
                                $p_userIdCreated,
                                $p_userIdModifyed);
   } // method getInstance

   /**
    * @param int $p_objId
    * @param mixed $p_ctrlObj
    *
    * @return HandledProduct
    */
   public static function getInstance_byObjId($p_objId, $p_ctrlObj)
   {
   	  // Set related controller-instance.
      $dbObj = self::getInstance_activeDatabaseConnection($p_ctrlObj);
      $dbPDOConnectionObj = $dbObj->getPDOConnectionInstance();
      if (self::doesDatabaseConnection_meetCriterias($dbPDOConnectionObj)) {
        if (self::doesExists($p_objId, $p_ctrlObj)) {
          $sql = 'SELECT hp.handling_id';
          $sql .= PHP_EOL;
          $sql .= ',hp.handling_type';
          $sql .= PHP_EOL;
          $sql .= ',hp.product_serial_number';
          $sql .= PHP_EOL;
          $sql .= ',hp.product_was_demo';
          $sql .= PHP_EOL;
          $sql .= ',hp.custom_warranty_months';
          $sql .= PHP_EOL;
          $sql .= ',hp.product_date_sent';
          $sql .= PHP_EOL;
          $sql .= ',hp.custom_warranty_months';
          $sql .= PHP_EOL;
          $sql .= ',hp.handling_created';
          $sql .= PHP_EOL;
          $sql .= ',hp.handling_modifyed';
          $sql .= PHP_EOL;
          $sql .= ',hp.user_id_created';
          $sql .= PHP_EOL;
          $sql .= ',hp.user_id_modifyed';
          $sql .= PHP_EOL;
          $sql .= 'FROM headsetservice_dk.handled_products hp';
          $sql .= PHP_EOL;
          $sql .= 'WHERE hp.handling_id = :handling_id';

          // Prepare and execute the SQL-statement.
          $pdoStatementObj = $dbPDOConnectionObj->prepare($sql);
          if (!$pdoStatementObj) {
            trigger_error(__METHOD__ .': Uable to prepare the SQL-statement. The message was the following: '. $dbPDOConnectionObj->errorInfo(), E_USER_ERROR);
          } else {
            try {
              // Map parameters and execute.
              $pdoStatementObj->bindParam(':handling_id', $p_objId, PDO::PARAM_INT);
              $pdoStatementObj->execute();
//            return $dbObj->fetchObject2($pdoStatementObj, __CLASS__);

              $arrRowAssoc = $dbObj->fetchRow_asAssocArray($pdoStatementObj);              
              return self::getInstance($arrRowAssoc['handling_id'],
                                       $arrRowAssoc['handling_type'],
                                       $arrRowAssoc['product_serial_number'],
                                       $arrRowAssoc['product_was_demo'],
                                       $arrRowAssoc['custom_warranty_months'],
                                       $arrRowAssoc['product_date_sent'],
                                       $arrRowAssoc['custom_warranty_months'],
                                       $arrRowAssoc['handling_created'],
                                       $arrRowAssoc['handling_modifyed'],
                                       $arrRowAssoc['user_id_created'],
                                       $arrRowAssoc['user_id_modifyed']);
            } catch (PDOException $e) {
              echo $e->getMessage();
            }
          }
        } else {
          trigger_error('Requested record with ID= ('. $p_objId.') does not exists ...', E_USER_WARNING);
        }
      } else {
        trigger_error('Unable to retrive record-data because of an unavaiable database-connection ...', E_USER_ERROR);
      }
   } // method getInstance_byObjId

   /**
    * Checks if a given record exists with the given unique primary-ID.
    *
    * @param int $p_objId
    * @param mixed $p_ctrlObj
    * 
    * @return boolean
    */
   public static function doesExists($p_objId, $p_ctrlObj)
   {
     $dbObj = self::getInstance_activeDatabaseConnection($p_ctrlObj);
     $dbPDOConnectionObj = $dbObj->getPDOConnectionInstance();
     if (self::doesDatabaseConnection_meetCriterias($dbPDOConnectionObj)) {
       // Setup the SQL-statement.
       $sqlStatement = 'SELECT count(hp.handling_id) AS NUM_RECORDS_FOUND';
       $sqlStatement .= PHP_EOL;
       $sqlStatement .= 'FROM headsetservice_dk.handled_products hp';
       $sqlStatement .= PHP_EOL;
       $sqlStatement .= 'WHERE hp.handling_id = :handling_id';
       $sqlStatement .= PHP_EOL;
       $sqlStatement .= 'LIMIT 1';

       // Prepare and execute the SQL-statement.
       $pdoStatementObj = $dbPDOConnectionObj->prepare($sqlStatement);
       if (!$pdoStatementObj) {
         trigger_error(__METHOD__ .': Uable to prepare the SQL-statement. The message was the following: '. $dbPDOConnectionObj->errorInfo(), E_USER_ERROR);
       } else {
         // Execute and return the boolean-result.
         try {
           // Map parameters
           $pdoStatementObj->bindParam(':handling_id', $p_objId, PDO::PARAM_INT);
           return $dbObj->fetchBooleanResult($pdoStatementObj);
         } catch (PDOException $e) {
           echo $e->getMessage();
         }
       }
     } else {
       trigger_error('Unable to check if a record-id allready exists because of unavaiable active database-connection ...', E_USER_ERROR);
     }
  } // method doesExists

  public static function doesSerialNumberExists($p_serialNumber, $p_ctrlObj)
  {
     // Eg. to print '%' character you need to escape it with itself.
     $paramHandledSerialNumber = sprintf("%%%s%%", $p_serialNumber);
     $paramHandledProductType = 1; // Sold-product

     $dbObj = self::getInstance_activeDatabaseConnection($p_ctrlObj);
     $dbPDOConnectionObj = $dbObj->getPDOConnectionInstance();
     if (self::doesDatabaseConnection_meetCriterias($dbPDOConnectionObj)) {
       // Setup the SQL-statement.
       $sql = 'SELECT hp.handling_id AS handling_id';
       $sql .= PHP_EOL;
       $sql .= 'FROM headsetservice_dk.handled_products hp';
       $sql .= PHP_EOL;
       $sql .= 'WHERE hp.product_serial_number LIKE :serial_number';
       $sql .= PHP_EOL;
       $sql .= 'AND hp.handling_type = :handling_type';
       $sql .= PHP_EOL;
       $sql .= 'ORDER BY hp.product_date_sent DESC'; // Allways take the most recent.
       $sql .= PHP_EOL;
       $sql .= 'LIMIT 1';

       // Prepare and execute the SQL-statement.
       $pdoStatementObj = $dbPDOConnectionObj->prepare($sql);
       if (!$pdoStatementObj) {
         trigger_error(__METHOD__ .': Uable to prepare the SQL-statement. The message was the following: '. $dbPDOConnectionObj->errorInfo(), E_USER_ERROR);
       } else {
         try {
           // Map parameters and execute.
           $pdoStatementObj->bindParam(':serial_number', $paramHandledSerialNumber, PDO::PARAM_STR);
           $pdoStatementObj->bindParam(':handling_type', $paramHandledProductType, PDO::PARAM_INT);
           return $dbObj->fetchFirstColumn($pdoStatementObj);
         } catch (PDOException $e) {
           echo $e->getMessage();
         }
       }
     } else {
       trigger_error('Unable to check if the record exists because of unavaiable active database-connection ...', E_USER_ERROR);
     }
  } // method doesSerialNumberExists

  public function addPersistentObj($p_dbObj)
  {
     $dbPDOConnectionObj = $p_dbObj->getPDOConnectionInstance();
     if (self::doesDatabaseConnection_meetCriterias($dbPDOConnectionObj)) {
       // Setup the SQL-statement.
       $sql = 'INSERT INTO handled_products';
       $sql .= PHP_EOL;
       $sql .= '(handling_type';
       $sql .= PHP_EOL;
       $sql .= ', product_serial_number';
       $sql .= PHP_EOL;
       $sql .= ', product_date_sent';
       $sql .= PHP_EOL;
       $sql .= ', product_was_demo';
       $sql .= PHP_EOL;
       $sql .= ', custom_warranty_months';
       $sql .= PHP_EOL;
       $sql .= ', user_id_created';
       $sql .= PHP_EOL;
       $sql .= ')';
       $sql .= PHP_EOL;
       $sql .= 'VALUES (:handling_type';
       $sql .= PHP_EOL;
       $sql .= ', :product_serial_number';
       $sql .= PHP_EOL;
       $sql .= ', :product_date_sent';
       $sql .= PHP_EOL;
       $sql .= ', :product_was_demo';
       $sql .= PHP_EOL;
       $sql .= ', :custom_warranty_months';
       $sql .= PHP_EOL;
       $sql .= ', :user_id_created';
       $sql .= PHP_EOL;
       $sql .= ')';

       // Prepare and execute the SQL-statement.
       $pdoStatementObj = $dbPDOConnectionObj->prepare($sql);
       if (!$pdoStatementObj) {
         trigger_error(__METHOD__ .': Uable to prepare the SQL-statement. The message was the following: '. $dbPDOConnectionObj->errorInfo(), E_USER_ERROR);
       } else {
         try {
           // Map parameters and execute.
           $paramHandlingType = $this->getAttr_handling_type();
           $paramProductHandlingNumber = $this->getAttr_product_serial_number();
           $paramProductDateSent = $this->getAttr_product_date_sent();
           $paramProductWasDemo = $this->getAttr_product_was_demo();
           $paramCustomWarrantyMonths = $this->getAttr_custom_warranty_months();
           $paramUserIdCreated = $this->getAttr_user_id_created();

           $pdoStatementObj->bindParam(':product_serial_number', $paramProductHandlingNumber, PDO::PARAM_STR);
           $pdoStatementObj->bindParam(':handling_type', $paramHandlingType, PDO::PARAM_INT);
           $pdoStatementObj->bindParam(':product_date_sent', $paramProductDateSent, PDO::PARAM_STR);
           $pdoStatementObj->bindParam(':product_was_demo', $paramProductWasDemo, PDO::PARAM_STR);
           $pdoStatementObj->bindParam(':custom_warranty_months', $paramCustomWarrantyMonths, PDO::PARAM_INT);
           $pdoStatementObj->bindParam(':user_id_created', $paramUserIdCreated, PDO::PARAM_INT);
           try {
             return $pdoStatementObj->execute();  
           } catch (PDOException $e) {
            echo $e->getMessage();
           }
         } catch (PDOException $e) {
           echo $e->getMessage();
         }
       }
     } else {
       trigger_error('Unable to check if the record exists because of unavaiable active database-connection ...', E_USER_ERROR);
     }
  } // method addPersistentObj
} // End class