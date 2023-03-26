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

/**
 * Filename     : timesheet.class.php
 * Language     : PHP v7.4
 * Date created : 29/09-2022, Ivan
 * Last modified: 25/03-2023, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2022 by Ivan Mark Andersen
 *
 * Description:
 *  A timesheet model-class that handels access to data and attributes and persisting data into the database.
 */
class Timesheet extends StdModel implements SaveableObjectInterface
{
    const db_table_name = 'timesheet';
    const db_table_alias = 't';

    /**
     * A Universally Unique IDentifier (UUID) its unique in the whole world.
     * Using UUIDs makes the amount of tubles that are possible to add in the database endless.
     * @var string
     */
    protected $timesheet_uuid;

    /**
     * @var string
     */
    protected $employee_uuid;

    /**
     * @var DateTime
     */
    protected $timesheet_work_date;

    /**
     * @var float
     */
    protected $timesheet_hours_regular;

    /**
     * @var float
     */
    protected $timesheet_hours_overtime;
  
    /**
     * @var float
     */
    protected $timesheet_hours_break;
  
    /**
     * @var DateTime
     */
    protected $created_at;

    /**
     * Constructor
     */
    public function __construct(string $p_timesheetUUID ='',
                                string $p_employeeUUID ='',
                                string $p_isoWorkDate ='',
                                float $p_hoursRegular =0,
                                float $p_hoursOvertime =0,
                                float $p_hoursBreak =0) {
        // Initalize the super-class of the instance.
        parent::__construct();
        
        // Initalize the attributes of the instance.
        $this->setAttr_timesheet_uuid($p_timesheetUUID);
        $this->setAttr_employee_uuid($p_employeeUUID);
        $this->setAttr_timesheet_work_date($p_isoWorkDate);
        // Timesheet hours attributes
        $this->setAttr_timesheet_hours_regular($p_hoursRegular);
        $this->setAttr_timesheet_hours_overtime($p_hoursOvertime);
        $this->setAttr_timesheet_hours_break($p_hoursBreak);
    }

    public function __destruct() {
      parent::__destruct();
    }

    /**
     * @return Timesheet
     */
    public static function getInstance(string $p_timesheetUUID ='',
                                       string $p_employeeUUID ='',
                                       string $p_isoWorkDate ='',
                                       float $p_hoursRegular =0,
                                       float $p_hoursOvertime =0,
                                       float $p_hoursBreak =0) : Timesheet {
      return new Timesheet($p_timesheetUUID,
                           $p_employeeUUID,
                           $p_isoWorkDate,
                           $p_hoursRegular,
                           $p_hoursOvertime,
                           $p_hoursBreak);
    }
  
    /**
     * Timesheet-UUID is the unique-identification of a timesheet.
     * The UUID that MySQL is using is a 128-bit number repesented by a string that consists of 5 hexadecimal numbers.
     */
    public function setAttr_timesheet_uuid(string $p_timesheetUUID ='') : void {
        $this->timesheet_uuid = $p_timesheetUUID;
    }

    public function getAttr_timesheet_uuid() : string {
        return $this->timesheet_uuid;
    }

    /**
     * @param string $p_employeeUUID
     */
    public function setAttr_employee_uuid(string $p_employeeUUID ='') : void {
        $this->employee_uuid = $p_employeeUUID;
    }

    /**
     * Returns the Universally Unique IDentifier (UUID) of an associated employee.
     * @return string
     */
    public function getAttr_employee_uuid() : string {
        return $this->employee_uuid;
    }
  
    /**
     * @param string $p_isoWorkDate
     * @return void
     */
    public function setAttr_timesheet_work_date(string $p_isoWorkDate) : void {
      $this->timesheet_work_date = CustomDateTime::getDateTimeInstance_fromFormatedDate($p_isoWorkDate);
    }

    /**
     * @param int $p_workDate_year
     * @param int $p_workDate_month
     * @param int $p_workDate_day
     * @return void
     */
    public function setAttr_timesheet_work_date_byYearMonthDay(int $p_workDate_year, int $p_workDate_month, int $p_workDate_day) : void {
        $customDateTime = CustomDateTime::getInstance();
        $customDateTime->setDate($p_workDate_year, $p_workDate_month, $p_workDate_day);

        $this->timesheet_work_date = $customDateTime->getInstance_dateTime();
    }

    /**
     * @return DateTime
     */
    public function getAttr_timesheet_work_date() : DateTime {
        return $this->timesheet_work_date;
    }

    /**
     * @param float $p_hoursRegular Default 0
     */
    public function setAttr_timesheet_hours_regular(float $p_hoursRegular =0) : void {
        $this->timesheet_hours_regular = (float) $p_hoursRegular;
    }

    /**
     * @return float
     */
    public function getAttr_timesheet_hours_regular() : float {
        return $this->timesheet_hours_regular;
    }

    /**
     * @param float $p_hoursOvertime Default 0
     * @return void
     */
    public function setAttr_timesheet_hours_overtime(float $p_hoursOvertime =0) : void {
        $this->timesheet_hours_overtime = (float) $p_hoursOvertime;
    }

    /**
     * @return float
     */
    public function getAttr_timesheet_hours_overtime() : float {
        return $this->timesheet_hours_overtime;
    }

    /**
     * @param float $p_hoursBreak Default 0
     * @return void
     */
    public function setAttr_timesheet_hours_break(float $p_hoursBreak =0) : void {
        $this->timesheet_hours_break = (float) $p_hoursBreak;
    }

    /**
     * @return float
     */
    public function getAttr_timesheet_hours_break() : float {
        return $this->timesheet_hours_break;
    }

    /**
     * Checks if a given record exists with the given unique primary-UUID.
     *
     * @param DBAbstraction $p_dbAbstraction
     * @param string $p_objUuid
     * @return bool
    */
    public static function doesExists(DBAbstraction $p_dbAbstraction, string $p_objUuid) : bool {
      $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
      if (self::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
        // Setup the SQL-statement.
        $sql = 'SELECT count(t.employee_uuid) AS NUM_RECORDS_FOUND';
        $sql .= PHP_EOL;
        $sql .= sprintf('FROM %s t', self::db_table_name);
        $sql .= PHP_EOL;
        $sql .= 'WHERE t.timesheet_uuid = :timesheet_uuid';
        $sql .= PHP_EOL;
        $sql .= 'LIMIT 1';

        // Prepare and execute the SQL-statement.
        $pdoStatement = $dbPDOConnection->prepare($sql);
        if (!$pdoStatement) {
          trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
        } else {
          try {
            // Map parameters
            $pdoStatement->bindParam(':timesheet_uuid', $p_objUuid, PDO::PARAM_STR);
            // Execute and return the boolean-result.
            return $p_dbAbstraction->fetchBooleanResult($pdoStatement);
          } catch (PDOException $e) {
            echo $e->getMessage();
          }
        }
      } else {
        trigger_error('Unable to check if a record-id allready exists because of unavaiable active database-connection ...', E_USER_ERROR);
      }
    }

    /**
     * @param DBAbstraction $p_dbAbstraction,
     * @param string $p_timesheetUuid UUID of the object that we want to retrive from the database.
     * @return Timesheet
     */
    public static function getInstance_byObjUuid(DBAbstraction $p_dbAbstraction, string $p_timesheetUuid) : Timesheet {
       // Retrive PDO database-connection.
       $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
       if (self::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
         if (self::doesExists($p_dbAbstraction, $p_timesheetUuid)) {
           $sql = 'SELECT timesheet_uuid';
           $sql .= PHP_EOL;
           $sql .= ',employee_uuid';
           $sql .= PHP_EOL;
           $sql .= ',timesheet_work_date';
           $sql .= PHP_EOL;
           $sql .= ',timesheet_hours_regular';
           $sql .= PHP_EOL;
           $sql .= ',timesheet_hours_overtime';
           $sql .= PHP_EOL;
           $sql .= ',timesheet_hours_break';
           $sql .= PHP_EOL;
           $sql .= sprintf('FROM %s', self::db_table_name);
           $sql .= PHP_EOL;
           $sql .= 'WHERE timesheet_uuid = :timesheet_uuid';
 
           // Prepare and execute the SQL-statement.
           $pdoStatement = $dbPDOConnection->prepare($sql);
           if (!$pdoStatement) {
             trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
           } else {
             try {
               // Map parameters and execute.
               $pdoStatement->bindParam(':timesheet_uuid', $p_timesheetUuid, PDO::PARAM_STR);
               $pdoStatement->execute();
 
               $arrRowAssoc = $p_dbAbstraction->fetchRow_asAssocArray($pdoStatement);
               return self::getInstance($arrRowAssoc['timesheet_uuid'],
                                        $arrRowAssoc['employee_uuid'],
                                        $arrRowAssoc['timesheet_work_date'],
                                        $arrRowAssoc['timesheet_hours_regular'],
                                        $arrRowAssoc['timesheet_hours_overtime'],
                                        $arrRowAssoc['timesheet_hours_break']);
             } catch (PDOException $e) {
               echo $e->getMessage();
             }
           }
         } else {
           trigger_error('Requested record with UUID: '. $p_timesheetUuid.' does not exists ...', E_USER_NOTICE);
         }
       } else {
         trigger_error('Unable to retrive record-data because of an unavaiable database-connection ...', E_USER_ERROR);
       }
    }

    /**
     * @param DBAbstraction $p_dbAbstraction,
     * @param string $p_employeeUuid eg: '597e8483-467d-11ed-b005-1c1bb5a9bf9b'
     * @param string $p_isoDateFrom
     * @param string $p_isoDateTo
     */
    public static function retriveRegisteredData_asAssocArray(DBAbstraction $p_dbAbstraction,
                                                           string $p_employeeUUID,
                                                           string $p_isoDateFrom,
                                                           string $p_isoDateTo
                                                          ) {
       // Retrive PDO database-connection.
       $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
       if (self::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
         // Setup the SQL-statment.
         $sql = 'SELECT t.timesheet_uuid, t.employee_uuid, t.timesheet_work_date, t.timesheet_hours_regular, t.timesheet_hours_overtime, t.timesheet_hours_break';
         $sql .= PHP_EOL;
         $sql .= sprintf('FROM %s %s', self::db_table_name, self::db_table_alias);
         $sql .= PHP_EOL;
         $sql .= sprintf('WHERE %s.employee_uuid = :timesheet_employee_uuid', self::db_table_alias);
         $sql .= PHP_EOL;
         $sql .= sprintf('AND %s.timesheet_work_date BETWEEN :week_from_date AND :week_to_date', self::db_table_alias);
         $sql .= PHP_EOL;
         $sql .= sprintf('ORDER BY %s.timesheet_work_date ASC', self::db_table_alias);

         // Prepare and execute the SQL-statement.
         $pdoStatement = $dbPDOConnection->prepare($sql);
         if (!$pdoStatement) {
           trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
         } else {
           try {
            // Map parameters and execute.
            $pdoStatement->bindParam(':timesheet_employee_uuid', $p_employeeUUID, PDO::PARAM_STR);
            $pdoStatement->bindParam(':week_from_date', $p_isoDateFrom, PDO::PARAM_STR);
            $pdoStatement->bindParam(':week_to_date', $p_isoDateTo, PDO::PARAM_STR);

            // Run the SQL-statment against the database.
            $pdoStatement->execute();

            // Make the array optimal to find the data
            $arrEmployeeWork = $p_dbAbstraction->fetchAll_asAssocArray($pdoStatement);
            foreach ($arrEmployeeWork as $key => $value) {
              $optimalKey = $arrEmployeeWork[$key]['timesheet_work_date'];
              unset($arrEmployeeWork[$key]);
              $arrEmployeeWork[$optimalKey] = $value;
            }

            return $arrEmployeeWork;
           } catch (PDOException $e) {
              echo $e->getMessage();
           }
         }
      } else {
          trigger_error('Unable to retrive record-data because of an unavaiable database-connection ...', E_USER_ERROR);
      }
    }

    /**
     * @param DBAbstraction $p_dbAbstraction
     * @param string $p_employeeUuid Default blank.
     * @param string $p_isoDateFrom ISO start-date of the period.
     * @param string $p_isoDateTo ISO end-date of the period.
     */
    public static function retriveAccumulatedHours(DBAbstraction $p_dbAbstraction,
                                            string $p_employeeUuid ='',
                                            string $p_isoDateFrom ='',
                                            string $p_isoDateTo =''
                                           ) {
       // Retrive PDO database-connection.
       $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
       if (self::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
         /* Setup SQL-statement that calculates the total regular, overtime & break hours for a given period. */
         $sql = "SELECT SUM(t.timesheet_hours_regular) AS total_hours_regular, SUM(t.timesheet_hours_overtime) AS total_hours_overtime, SUM(t.timesheet_hours_break) AS total_hours_break";
         $sql .= PHP_EOL;
         $sql .= sprintf('FROM %s %s', self::db_table_name, self::db_table_alias);
         $sql .= PHP_EOL;
         $sql .= "WHERE t.employee_uuid = :employee_uuid";
         $sql .= PHP_EOL;
         $sql .= "AND t.timesheet_work_date BETWEEN :period_from_date AND :period_to_date";
         $sql .= PHP_EOL;
         $sql .= 'GROUP BY t.employee_uuid';

         // Prepare and execute the SQL-statement.
         $pdoStatement = $dbPDOConnection->prepare($sql);
         if (!$pdoStatement) {
           trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
         } else {
           try {
            // Map parameters and execute.
            $pdoStatement->bindParam(':employee_uuid', $p_employeeUuid, PDO::PARAM_STR);
            $pdoStatement->bindParam(':period_from_date', $p_isoDateFrom, PDO::PARAM_STR);
            $pdoStatement->bindParam(':period_to_date', $p_isoDateTo, PDO::PARAM_STR);

            // Run the SQL-statment against the database.
            $pdoStatement->execute();

            // Make the array optimal to find the data
            return $p_dbAbstraction->fetchAll_asAssocArray($pdoStatement);
           } catch (PDOException $e) {
             echo $e->getMessage();
           }
         }
      }
    }

    // Implementing the methods of savable-object interface

    /**
     * @param DBAbstraction $p_dbAbstraction
     * @return bool Returns true on success or false on failure.
     */
    public function addPersistentRecord(DBAbstraction $p_dbAbstraction) : bool {
       $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
       if (self::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
         // Setup the SQL-statement.
         $sql = sprintf('INSERT INTO %s', self::db_table_name);
         $sql .= PHP_EOL;
         $sql .= '(employee_uuid';
         $sql .= PHP_EOL;
         $sql .= ', timesheet_work_date';
         $sql .= PHP_EOL;
         $sql .= ', timesheet_hours_regular';
         $sql .= PHP_EOL;
         $sql .= ', timesheet_hours_overtime';
         $sql .= PHP_EOL;
         $sql .= ', timesheet_hours_break';
         $sql .= ')';
         $sql .= PHP_EOL;
         $sql .= 'VALUES (:employee_uuid';
         $sql .= PHP_EOL;
         $sql .= ', :timesheet_work_date';
         $sql .= PHP_EOL;
         $sql .= ', :timesheet_hours_regular';
         $sql .= PHP_EOL;
         $sql .= ', :timesheet_hours_overtime';
         $sql .= PHP_EOL;
         $sql .= ', :timesheet_hours_break';
         $sql .= ')';

         // Prepare and execute the SQL-statement.
         $pdoStatement = $dbPDOConnection->prepare($sql);
         if (!$pdoStatement) {
           trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
         } else {
           try {
             // Map parameters and execute.
             $paramEmployeeUuid = $this->getAttr_employee_uuid();
             $paramTimesheetWorkDate = $this->getAttr_timesheet_work_date();
             $paramTimesheetWorkISODate = $paramTimesheetWorkDate->format(CustomDateTime::getISODateFormat());
             $paramTimesheetHoursRegular = $this->getAttr_timesheet_hours_regular();
             $paramTimesheetHoursOvertime = $this->getAttr_timesheet_hours_overtime();
             $paramTimesheetHoursBreak = $this->getAttr_timesheet_hours_break();
  
             $pdoStatement->bindParam(':employee_uuid', $paramEmployeeUuid, PDO::PARAM_STR);
             $pdoStatement->bindParam(':timesheet_work_date', $paramTimesheetWorkISODate, PDO::PARAM_STR);
             $pdoStatement->bindParam(':timesheet_hours_regular', $paramTimesheetHoursRegular, PDO::PARAM_STR);
             $pdoStatement->bindParam(':timesheet_hours_overtime', $paramTimesheetHoursOvertime, PDO::PARAM_STR);
             $pdoStatement->bindParam(':timesheet_hours_break', $paramTimesheetHoursBreak, PDO::PARAM_STR);

             try {
               return $pdoStatement->execute();
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
          $sql .= 'SET timesheet_hours_regular = :timesheet_hours_regular';
          $sql .= PHP_EOL;
          $sql .= ', timesheet_hours_overtime = :timesheet_hours_overtime';
          $sql .= PHP_EOL;
          $sql .= ', timesheet_hours_break = :timesheet_hours_break';
          $sql .= PHP_EOL;
          $sql .= 'WHERE timesheet_uuid = :timesheet_uuid';

          // Prepare and execute the SQL-statement.
          $pdoStatement = $dbPDOConnection->prepare($sql);
          if (!$pdoStatement) {
            trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
          } else {
            try {
              // Map parameters and execute.
              $paramTimesheetUuid = $this->getAttr_timesheet_uuid();
              $paramTimesheetHoursRegular = $this->getAttr_timesheet_hours_regular();
              $paramTimesheetHoursOvertime = $this->getAttr_timesheet_hours_overtime();
              $paramTimesheetHoursBreak = $this->getAttr_timesheet_hours_break();
  
              $pdoStatement->bindParam(':timesheet_uuid', $paramTimesheetUuid, PDO::PARAM_STR);
              $pdoStatement->bindParam(':timesheet_hours_regular', $paramTimesheetHoursRegular, PDO::PARAM_STR);
              $pdoStatement->bindParam(':timesheet_hours_overtime', $paramTimesheetHoursOvertime, PDO::PARAM_STR);
              $pdoStatement->bindParam(':timesheet_hours_break', $paramTimesheetHoursBreak, PDO::PARAM_STR);

              try {
                return $pdoStatement->execute();
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
      $sql .= 'WHERE timesheet_uuid = :timesheet_uuid';

      // Prepare and execute the SQL-statement.
      $pdoStatementObj = $dbPDOConnection->prepare($sql);
      if (!$pdoStatementObj) {
        trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
      } else {
        try {
          // Map parameters and execute.
          $paramTimesheetUUID = $this->getAttr_timesheet_uuid();
          $pdoStatementObj->bindParam(':timesheet_uuid', $paramTimesheetUUID, PDO::PARAM_STR);
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