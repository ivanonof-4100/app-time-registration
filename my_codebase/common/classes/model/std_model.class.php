<?php
namespace Common\Classes\Model;

use Exception;
use PDO;
use Common\Classes\Db\DBAbstraction;

/**
 *   Script-name: std_model.class.php
 *      Language: PHP v7.4
 *  Date created: 24/01-2014, Ivan
 * Last modified: 08/10-2022, Ivan
 *    Developers: @author Ivan Mark Andersen <ivanonof@gmail.com>
 *    @copyright: Copyright (C) 2014 by Ivan Mark Andersen
 *
 * Description:
 *  Wraps a generic implementation of a standard model-object in my custom MVC-framework.
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
  public function __get($p_propertyName) {
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
  public function doesPropertyExists($p_propertyName) : bool {
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

  // All the _rowstate-related methods.

  /**
   * Sets the value of the attribute _rowstate of the instance.
   * @param int $p_rowstate
   */
  protected function setAttr_rowstate(int $p_rowstate) {
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
   * @param resource $p_dbPDOConnection
   * @return bool
   */
  public static function doesDatabaseConnection_meetCriterias($p_dbPDOConnection) : bool {
    return (is_object($p_dbPDOConnection) && ($p_dbPDOConnection instanceof PDO));
  }

  /**
   * OLD way of doing things!
   * Retrives the instance of the active database-connection stored in the codebase-registry.
   * @param mixed $p_ctrlObj
   */
  public static function getInstance_activeDatabaseConnection($p_ctrlObj) {
	   if (is_object($p_ctrlObj) && is_subclass_of($p_ctrlObj, 'StdController')) {
	     // Return the database-connection hold in the codebase-registry.
       $codebaseRegistryObj = $p_ctrlObj->getInstance_codebaseRegistry();
       return $codebaseRegistryObj->getInstance_dbConnection();
	   } else {
       trigger_error(__METHOD__ .': Unable to retrieve active-database connection ...', E_USER_ERROR);
	   }
  }
} // End class