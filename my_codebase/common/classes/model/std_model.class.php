<?php
namespace Common\Classes\Model;

use Exception;
use Common\Classes\Db\DBAbstraction;

/**
 *   Filename: std_model.class.php
 *      Language: PHP v7.4
 *  Date created: 02/10-2022, Ivan
 * Last modified: 25/03-2023, Ivan
 *    Developers: @author Ivan Mark Andersen <ivanonof@gmail.com>
 *    @copyright: Copyright (C) 2023 by Ivan Mark Andersen
 *
 * Description:
 * Wraps a generic implementation of a standard model-object in my custom MVC-framework.
*/

// Exceptions related to this class.
class InvalidPropertyReferenceException extends Exception {}

abstract class StdModel
{
  // Constants
  const ROWSTATE_UNCHANGED =0;
  const ROWSTATE_CHANGED =1;
  const ROWSTATE_DELETED =2;
  const ROWSTATE_INSERTED =3;

  // Attributes

  /**
   * Must always be the first attribute as long as it is used for sorting like in the save method.
   * @var int $_rowstate
  */
  protected $_rowstate;

  /**
   *  Constructor initalizes the super-class of a newly created model-instance.
   */
  public function __construct() {
     $this->resetRowstate();
  }

  /**
   * Default deconstructor.
   */
  public function __destruct() {
  }

  /**
   * Default copy-constructor.
   */
  public function __clone() {
  }

  /**
   * Sets an data-attribute with-in the object using its set-method and the given value.
   *
   * @param string $p_objAttr
   * @param mixed $p_attrValue
   */
  public function setAttr($p_objAttr, $p_attrValue) {
     $setMethod = 'setAttr_'.$p_objAttr;
     if (method_exists($this, $setMethod)) {
       $this->$setMethod($p_attrValue);
     } else {
       trigger_error(__METHOD__ .': Unable to call an undefined set-method ('.$setMethod .' of the class.');
     }     
  }

  /**
   * This magic-method is triggered when an unreachable object-property was tryed set to a value.
   *
   * @param string $p_propertyName
   * @param mixed $p_propertyValue
   * @return void
   * @throws InvalidPropertyReferenceException
   */
  public function __set($p_propertyName, $p_propertyValue) : void {
     if (!$this->doesPropertyExists($p_propertyName)) {
       throw new InvalidPropertyReferenceException(sprintf("Referenced object-property %s doesn\'t exists ...", $p_propertyName));
     } else {
       $this->setAttr($p_propertyName, $p_propertyValue);
     }
  }

  /**
   * This magic-method is triggered when an unreachable object-property is accessed.
   * 
   * @param string $p_propertyName
   * @return mixed
   * @throws InvalidPropertyReferenceException
   */
  public function __get(string $p_propertyName) {
     if (!$this->doesPropertyExists($p_propertyName)) {
       throw new InvalidPropertyReferenceException(sprintf("Referenced object-property %s doesn\'t exists ...", $p_propertyName));
     } else {
	     return $this->$p_propertyName;	
	   }
  }

  /**
   * Checks if the object or class has a property.
   *
   * @param string $p_propertyName
   * @return bool
   */
  public function doesPropertyExists(string $p_propertyName) : bool {
     return property_exists($this, $p_propertyName);
  }

  public static function getClassName($p_obj) : string {
     return get_class($p_obj);
  }

  /**
   * @return array
   */
  public static function getClassAttributes($p_obj) : array {
     return get_class_vars(self::getClassName($p_obj));
  }

  // All _rowstate-related methods.

  /**
   * Sets the value of the attribute _rowstate of the instance.
   * @param int $p_rowstate
   */
  protected function setAttr_rowstate(int $p_rowstate) : void {
    $this->_rowstate = (int) $p_rowstate;
  }

  /**
   * Returns the value of the attribute _rowstate of the instance.
   * @return int
   */
  protected function getAttr_rowstate() : int {
    return $this->_rowstate;
  }

  /**
   * Returns the objects current _rowstate.
   * @return int Returns the current value of the attribute _rowstate of the instance.
   */
  public function getCurrentRowstate() : int {
    return $this->getAttr_rowstate();
  }

  /**
   * Sets the _rowstate attribute of the instance to the UNCHANGED state, that is the default rowstate value.
   */
  public function resetRowstate() : void {
    // Default for the _rowstate
    $this->markAsUnchanged();
  }

  /**
   * Marks the current object as unchanged.
   * @return void
   */
  public function markAsUnchanged() : void {
    $this->setAttr_rowstate(self::ROWSTATE_UNCHANGED);
  }

  /**
   * Marks the current object as changed and will be updated fysicaly when save method is called.
   * NOTE!: Note that the _rowstate attribute can only be set to changed if the current state of the row is NOT new or deleted.
   * @return void
   */
  public function markAsChanged() : void {
     // If the current rowstate is not either ROWSTATE_INSERTED or ROWSTATE_DELETED.
     if ((!$this->isMarkedAsInserted()) && (!$this->isMarkedAsDeleted())) {
       $this->setAttr_rowstate(self::ROWSTATE_CHANGED);
     }
  }

  /**
   * Marks the current object as inserted and will be inserted fysicaly when save method is called.
   * @return void
   */
  public function markAsInserted() : void {
     if ((!$this->isMarkedAsChanged()) && (!$this->isMarkedAsDeleted())) {
       $this->setAttr_rowstate(self::ROWSTATE_INSERTED);
     }
  }

  /**
   * Marks the current object as deleted and will be removed fysicaly when save method is called.
   * @return void
   */
  public function markAsDeleted() : void {
     if ($this->isMarkedAsInserted()) {
       $this->setAttr_rowstate(self::ROWSTATE_UNCHANGED);
     } else {
       $this->setAttr_rowstate(self::ROWSTATE_DELETED);
     }
  }

  /**
   * Returns a boolean-result on wheter or not the data of the instance is marked as inserted.
   * @return bool Returns TRUE, if the instance is marked as inserted, otherwise FALSE.
   */
  public function isMarkedAsInserted() : bool {
     return ($this->getAttr_rowstate() == self::ROWSTATE_INSERTED);
  }

  /**
   * Returns a boolean-result on wheter or not the data of the instance is marked as changed.
   * @return bool Returns TRUE, if the instance is marked as changed, otherwise FALSE.
   */
  public function isMarkedAsChanged() : bool {
     return ($this->getAttr_rowstate() == self::ROWSTATE_CHANGED);
  }

  /**
   * Returns a boolean-result on wether or not the data of the instance is marked as deleted.
   * @return bool Returns TRUE, if the instance is marked as deleted, otherwise FALSE.
   */
  public function isMarkedAsDeleted() : bool {
     return ($this->getAttr_rowstate() == self::ROWSTATE_DELETED);
  }

  /**
   * Inserts the objects data in the database.
   * @param DBAbstraction $p_dbAbstraction
   * @return bool
   */
   public function addPersistentRecord(DBAbstraction $p_dbAbstraction) {
   }

   /**
    * Updates the objects data in the database.
    * @param DBAbstraction $p_dbAbstraction
    * @return bool
    */
   public function updPersistentRecord(DBAbstraction $p_dbAbstraction) {
   }
 
   /**
    * Deletes the objects data in the database.
    * @param DBAbstraction $p_dbAbstraction
    * @return bool
    */
   public function delPersistentRecord(DBAbstraction $p_dbAbstraction) {
   }

  /**
   * Saves the objects data in a persistent and generic way.
   * 
   * @param DBAbstraction $p_dbAbstraction
   * @param bool $p_handleTransaction Default boolean TRUE.
   * @return bool
   * @throws Exception
   */
  public function save(DBAbstraction $p_dbAbstraction, bool $p_handleTransaction =TRUE) : bool {
   /*
    * Check the _rowstate attribute of the instance to see how to save it in the database.
    * 
    * The _rowstate attribute can take the the following values:
    *  ROWSTATE_UNCHANGED: Row not touched, data is the same as since it was loaded from a database
    *                      Do nothing with this row!
    *  ROWSTATE_CHANGED  : Data has been changed and needs to be updated.
    *  ROWSTATE_DELETED  : Data has been marked as deleted and needs to be removed in database.
    *  ROWSTATE_INSERTED : New data has been added and needs to be added in the database too.
    */
   if (is_object($p_dbAbstraction) && ($p_dbAbstraction instanceof DBAbstraction)) {
     if ($p_handleTransaction) {
       // Start Transaction
       $wasSuccessful = $p_dbAbstraction->beginTransaction();
     }
   } else {
     trigger_error('Database-connection does not meet the criterias ...', E_USER_ERROR);
     exit(1);
   }

   try {
     if ($this->isMarkedAsChanged()) {
       $wasSuccessful = $this->updPersistentRecord($p_dbAbstraction);
     } elseif ($this->isMarkedAsInserted()) {
       $wasSuccessful = $this->addPersistentRecord($p_dbAbstraction);
     } elseif ($this->isMarkedAsDeleted()) {
       $wasSuccessful = $this->delPersistentRecord($p_dbAbstraction);
     }
 
     if ($p_handleTransaction) {
       if ($wasSuccessful) {
         $wasSuccessful = $p_dbAbstraction->commit();
       } else {
         $wasNotSuccessful = $p_dbAbstraction->rollback();
       }
     }

     if ($wasSuccessful) {
       // Reset the state of the record (_rowstate)
       $this->resetRowstate();
     }

     // Return the result of persistent operation.
     return ($wasSuccessful === TRUE) ? $wasSuccessful : FALSE;
   } catch (Exception $e) {
     // Re-throw exception again to catch at a higher controller-level.
     throw new Exception(sprintf('An error occured trying to persist data: %s', $e->getMessage()));
   }
 }
}