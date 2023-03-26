<?php
namespace App\Modules\Timesheets\Classes\Model;

use Exception;
use Common\Classes\Db\DBAbstraction;
use Common\Classes\Model\StdModel;
use Common\Classes\Model\SaveableObjectInterface;
use Common\Classes\Datetime\CustomDateTime;
use DateTime;
use PDO;
use PDOStatement;
use PDOException;
use UnexpectedValueException;

/**
 * Filename     : employee.class.php
 * Language     : PHP v7.4
 * Date created : 29/09-2022, Ivan
 * Last modified: 29/09-2022, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2022 by Ivan Mark Andersen
 *
 * Description:
 *  A employee model-class that handels access to data and attributes and persisting data into the database.
 */
class Employee extends StdModel implements SaveableObjectInterface
{
    const db_table_name = 'employee';

    const PERSON_GENDER_FEMALE = 'F';
    const PERSON_GENDER_MALE = 'M';

    /**
     * A Universally Unique IDentifier (UUID) its unique in the whole world.
     * Using UUIDs makes the amount of tubles that are possible to add in the database endless.
     * @var string
     */
    protected $employee_uuid;

    /**
     * @var string
     */
    protected $person_gender;

    /**
     * @var string
     */
    protected $person_first_name;

    /**
     * @var string
     */
    protected $person_middle_name;
  
    /**
     * @var string
     */
    protected $person_last_name;

    /**
     * @var DateTime
     */
    protected $person_birthday;

    /**
     * Constructor
     *
     * @param string $p_employeeUUID
     * @param string $p_personGender Possible values 'F' => 'Female' or 'M' => 'Male'.
     * 
     * @param int $p_birthdayYear
     * @param int $p_birthdayMonth
     * @param int $p_birthdayDay
     * @param string $p_personFirstName Default blank.
     * @param string $p_personMiddleName Default blank.
     * @param string $p_personLastName Default blank.
     */
    public function __construct(string $p_employeeUUID ='',
                                string $p_personGender =self::PERSON_GENDER_MALE,
                                int $p_birthdayYear =0,
                                int $p_birthdayMonth =0,
                                int $p_birthdayDay =0,
                                string $p_personFirstName = '',
                                string $p_personMiddleName = '',
                                string $p_personLastName = '') {
        // Initalize the super-class of the instance.
        parent::__construct();

        // Initalize the attributes of the instance.
        $this->setAttr_employee_uuid($p_employeeUUID);
        $this->setAttr_person_gender($p_personGender);
        // $this->setAttr_person_birthday($p_birthdayYear, $p_birthdayMonth, $p_birthdayDay);
        $this->setAttr_person_first_name($p_personFirstName);
        $this->setAttr_person_middle_name($p_personMiddleName);
        $this->setAttr_person_last_name($p_personLastName);
    }

    public function __destruct() {
      parent::__destruct();
    }

    /**
     * @return Employee
     */
    public static function getInstance(string $p_employeeUUID ='',
                                       string $p_personGender =self::PERSON_GENDER_MALE,
                                       string $p_personFirstName = '',
                                       string $p_personMiddleName = '',
                                       string $p_personLastName = '',
                                       string $p_isoBirthday ='') : Employee {
      try {
        $dateTimeInstance = CustomDateTime::getDateTimeInstance_fromFormatedDate($p_isoBirthday);
        $employee = self::getInstance($p_employeeUUID,
                                      $p_personGender,
                                      $p_personFirstName,
                                      $p_personMiddleName,
                                      $p_personLastName);

        // Set birthday by DateTime instance.
        $employee->setAttr_person_birthday_byDateTime($dateTimeInstance);
        return $employee;
      } catch (UnexpectedValueException $e) {
        echo $e->getMessage();
        exit(4);
      }
    }

    public function setAttr_employee_uuid(string $p_employeeUUID ='') : void {
      $this->employee_uuid = $p_employeeUUID;
    }

    public function getAttr_employee_uuid() : string {
      return $this->employee_uuid;
    }

    /**
     * @param string $p_gender Possible values self::PERSON_GENDER_FEMALE, self::PERSON_GENDER_MALE
     */
    public function setAttr_person_gender($p_gender) : void {
      $this->person_gender = $p_gender;
    }

    public function getAttr_person_gender() : string {
      return $this->person_gender;
    }

    /**
     * @return bool Returns boolean TRUE, if the gender is female otherwise FALSE.
     */
    public function isGenderFemale() : bool {
      return ($this->getAttr_person_gender() == self::PERSON_GENDER_FEMALE);
    }

    /**
     * @return bool Returns boolean TRUE, if the gender is male otherwise FALSE.
     */
    public function isGenderMale() : bool {
      return ($this->getAttr_person_gender() == self::PERSON_GENDER_MALE);
    }

    /**
     * @param string $p_firstName Default blank.
     */
    public function setAttr_person_first_name(string $p_firstName ='') : void {
      $this->person_first_name = $p_firstName;
    }

    public function getAttr_person_first_name() : string {
      return $this->person_first_name;
    }

    public function setAttr_person_middle_name(string $p_middleName ='') : void {
      $this->person_middle_name = $p_middleName;
    }

    public function getAttr_person_middle_name() : string {
      return $this->person_middle_name;
    }

    /**
     * @return bool Returns boolean TRUE, if the person has a middle name.
     */
    public function hasMiddleName() : bool {
      $middleName = $this->getAttr_person_middle_name();
      if (empty($middleName)) {
        return FALSE;
      } else {
        return TRUE;
      }
    }

    /**
     * @param string $p_lastName Default blank.
     */
    public function setAttr_person_last_name(string $p_lastName = '') : void {
      $this->person_last_name = $p_lastName;
    }

    /**
     * @return string
     */
    public function getAttr_person_last_name() : string {
      return $this->person_last_name;
    }

    /**
     * @return string
     */
    public function getFullName() : string {
      if ($this->hasMiddleName()) {
        return printf('%s %s %s', $this->getAttr_person_first_name(), $this->getAttr_person_middle_name(), $this->getAttr_person_last_name());
      } else {
        return printf('%s %s', $this->getAttr_person_first_name(), $this->getAttr_person_last_name());
      }
    }

    /**
     * @param int $p_birthdayYear
     * @param int $p_birthdayMonth
     * @param int $p_birthdayDay
     * @return void
     */
    public function setAttr_person_birthday_byYearMonthDay(int $p_birthdayYear, int $p_birthdayMonth, int $p_birthdayDay) : void {
        $customDateTime = CustomDateTime::getInstance();
        $customDateTime->setDate($p_birthdayYear, $p_birthdayMonth, $p_birthdayDay);

        $this->person_birthday = $customDateTime->getInstance_dateTime();
    }

    /**
     * @param DateTime $p_birthdayDateTime
     */
    public function setAttr_person_birthday_byDateTime($p_birthdayDateTime) : void {
      $this->person_birthday = $p_birthdayDateTime;
    }
  
    /**
     * @return DateTime
     */
    public function getAttr_person_birthday() : DateTime {
      return $this->person_birthday;
    }

    /**
     * Checks if a given record exists with the given unique primary-ID.
     *
     * @param DBAbstraction $p_dbAbstraction
     * @param string $p_objUuid
     * @return bool
     */
    public static function doesExists(DBAbstraction $p_dbAbstraction, string $p_objUuid) : bool {
      $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
      if (self::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
        // Setup the SQL-statement.
        $sql = 'SELECT count(e.employee_uuid) AS NUM_RECORDS_FOUND';
        $sql .= PHP_EOL;
        $sql .= sprintf('FROM %s e', self::db_table_name);
        $sql .= PHP_EOL;
        $sql .= 'WHERE e.employee_uuid = :employee_uuid';
        $sql .= PHP_EOL;
        $sql .= 'LIMIT 1';

        // Prepare and execute the SQL-statement.
        $pdoStatementObj = $dbPDOConnection->prepare($sql);
        if (!$pdoStatementObj) {
          trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
        } else {
          // Execute and return the boolean-result.
          try {
            // Map parameters
            $pdoStatementObj->bindParam(':employee_uuid', $p_objUuid, PDO::PARAM_STR);
            return $p_dbAbstraction->fetchBooleanResult($pdoStatementObj);
          } catch (PDOException $e) {
            echo $e->getMessage();
          }
        }
      } else {
        trigger_error('Unable to check if a record-id allready exists because of unavaiable active database-connection ...', E_USER_ERROR);
      }
    }

    /**
     * @param DBAbstraction $p_dbAbstraction
     * @param string $p_objUuid UUID of the object that we want to retrive from the database. 
     */
    public static function getInstance_byObjUuid(DBAbstraction $p_dbAbstraction, string $p_objUuid) {
      // Retrive active PDO database-connection
      $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
      if (self::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
        if (self::doesExists($p_dbAbstraction, $p_objUuid)) {
          // Setup SQL-statement.
          $sql = 'SELECT employee_uuid';
          $sql .= PHP_EOL;
          $sql .= ',person_first_name';
          $sql .= PHP_EOL;
          $sql .= ',person_middle_name';
          $sql .= PHP_EOL;
          $sql .= ',person_last_name';
          $sql .= PHP_EOL;
          $sql .= ',person_gender';
          $sql .= PHP_EOL;
          $sql .= ',person_birthday';
          $sql .= PHP_EOL;
          $sql .= sprintf('FROM %s', self::db_table_name);
          $sql .= PHP_EOL;
          $sql .= 'WHERE employee_uuid = :employee_uuid';

          // Prepare and execute the SQL-statement.
          $pdoStatementObj = $dbPDOConnection->prepare($sql);
          if (!$pdoStatementObj) {
            trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
          } else {
            try {
              // Map parameters and execute.
              $pdoStatementObj->bindParam(':employee_uuid', $p_objUuid, PDO::PARAM_STR);
              $pdoStatementObj->execute();

              $arrRowAssoc = $p_dbAbstraction->fetchRow_asAssocArray($pdoStatementObj);              
              return self::getInstance($arrRowAssoc['employee_uuid'],
                                       $arrRowAssoc['person_gender'],
                                       $arrRowAssoc['person_first_name'],
                                       $arrRowAssoc['person_middle_name'],
                                       $arrRowAssoc['person_last_name'],
                                       $arrRowAssoc['person_birthday']
                                      );
            } catch (PDOException $e) {
              echo $e->getMessage();
            }
          }
        } else {
          trigger_error('Requested record with UUID: '. $p_objUuid.' does not exists ...', E_USER_NOTICE);
        }
      } else {
        trigger_error('Unable to retrive record-data because of an unavaiable database-connection ...', E_USER_ERROR);
      }
    }

    // Implementing the methods of savable-object interface

    /**
     * @param DBAbstraction $p_dbAbstraction
     * @return bool Returns boolean TRUE on success or FALSE on failure.
     */
    public function addPersistentRecord(DBAbstraction $p_dbAbstraction) : bool {
      $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
      if (self::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
         // Setup the SQL-statement.
         $sql = sprintf('INSERT INTO %s', self::db_table_name);
         $sql .= PHP_EOL;
         $sql .= '(employee_uuid';
         $sql .= PHP_EOL;
         $sql .= ', person_first_name';
         $sql .= PHP_EOL;
         $sql .= ', person_middle_name';
         $sql .= PHP_EOL;
         $sql .= ', person_last_name';
         $sql .= PHP_EOL;
         $sql .= ', person_gender';
         $sql .= PHP_EOL;
         $sql .= ', person_birthday';
         $sql .= ')';
         $sql .= PHP_EOL;
         $sql .= 'VALUES (:employee_uuid';
         $sql .= PHP_EOL;
         $sql .= ', :person_first_name';
         $sql .= PHP_EOL;
         $sql .= ', :person_middle_name';
         $sql .= PHP_EOL;
         $sql .= ', :person_last_name';
         $sql .= PHP_EOL;
         $sql .= ', :person_gender';
         $sql .= PHP_EOL;
         $sql .= ', :person_birthday';
         $sql .= ')';

         // Prepare and execute the SQL-statement.
         $pdoStatementObj = $dbPDOConnection->prepare($sql);
         if (!$pdoStatementObj) {
           trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
         } else {
           try {
             $isoDateformat = CustomDateTime::getISODateFormat();
             // Map parameters and execute.
             $paramEmployeeUuid = $this->getAttr_employee_uuid();
             $paramPersonFirstName = $this->getAttr_person_first_name();
             $paramPersonMiddleName = $this->getAttr_person_middle_name();
             $paramPersonLastName = $this->getAttr_person_last_name();
             $paramPersonGender = $this->getAttr_person_gender();
             $paramPersonBirthday = $this->getAttr_person_birthday();

             $pdoStatementObj->bindParam(':employee_uuid', $paramEmployeeUuid, PDO::PARAM_STR);
             $pdoStatementObj->bindParam(':person_first_name', $paramPersonFirstName, PDO::PARAM_STR);
             $pdoStatementObj->bindParam(':person_middle_name', $paramPersonMiddleName, PDO::PARAM_STR);
             $pdoStatementObj->bindParam(':person_last_name', $paramPersonLastName, PDO::PARAM_STR);
             $pdoStatementObj->bindParam(':person_gender', $paramPersonGender, PDO::PARAM_STR);
             $pdoStatementObj->bindParam(':person_birthday', $paramPersonBirthday->format($isoDateformat), PDO::PARAM_STR);
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
         trigger_error('Unable to execute insert-statment because of unavailable active database-connection ...', E_USER_ERROR);
       }
    }

    /**
     * @param DBAbstraction $p_dbAbstraction
     * @return bool
     */
    public function updPersistentRecord(DBAbstraction $p_dbAbstraction) : bool {
      $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
      if (self::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
          // Setup the SQL-statement.
          $sql = sprintf('UPDATE %s', self::db_table_name);
          $sql .= PHP_EOL;
          $sql .= 'SET person_first_name = :person_first_name';
          $sql .= PHP_EOL;
          $sql .= ', person_middle_name = :person_middle_name';
          $sql .= PHP_EOL;
          $sql .= ', person_last_name = :person_last_name';
          $sql .= PHP_EOL;
          $sql .= ', person_gender = :person_gender';
          $sql .= PHP_EOL;
          $sql .= ', person_birthday = :person_birthday';
          $sql .= PHP_EOL;
          $sql .= 'WHERE employee_uuid = :employee_uuid';

          // Prepare and execute the SQL-statement.
          $pdoStatementObj = $dbPDOConnection->prepare($sql);
          if (!$pdoStatementObj) {
            trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
          } else {
            try {
              $isoDateformat = CustomDateTime::getISODateFormat();
              // Map parameters and execute.
              $paramPersonFirstName = $this->getAttr_person_first_name();
              $paramPersonMiddleName = $this->getAttr_person_middle_name();
              $paramPersonLastName = $this->getAttr_person_last_name();
              $paramPersonGender = $this->getAttr_person_gender();
              $paramPersonBirthday = $this->getAttr_person_birthday();

              $pdoStatementObj->bindParam(':person_first_name', $paramPersonFirstName, PDO::PARAM_STR);
              $pdoStatementObj->bindParam(':person_middle_name', $paramPersonMiddleName, PDO::PARAM_STR);
              $pdoStatementObj->bindParam(':person_last_name', $paramPersonLastName, PDO::PARAM_STR);
              $pdoStatementObj->bindParam(':person_gender', $paramPersonGender, PDO::PARAM_STR);
              $pdoStatementObj->bindParam(':person_birthday', $paramPersonBirthday->format($isoDateformat), PDO::PARAM_STR);

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
         trigger_error('Unable execute update-statment because of unavailable active database-connection ...', E_USER_ERROR);
       }
    }

   /**
    * Deletes or removes data or rows from the database-table.
    * @param DBAbstraction $p_dbAbstraction
    * @return bool
    */
    public function delPersistentRecord(DBAbstraction $p_dbAbstraction) : bool {
      $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
      if (self::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
        // Setup the SQL-statement.
        $sql = sprintf('DELETE FROM %s', self::db_table_name);
        $sql .= PHP_EOL;
        $sql .= 'WHERE employee_uuid = :employee_uuid';

        // Prepare and execute the SQL-statement.
        $pdoStatementObj = $dbPDOConnection->prepare($sql);
        if (!$pdoStatementObj) {
          trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
        } else {
          try {
            // Map parameters and execute.
            $paramEmployeeUUID = $this->getAttr_employee_uuid();
            $pdoStatementObj->bindParam(':employee_uuid', $paramEmployeeUUID, PDO::PARAM_STR);
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
        trigger_error('Unable execute delete-statment because of unavailable active database-connection ...', E_USER_ERROR);
      }
    }
} // End class