<?php
namespace App\Modules\Timesheets\Classes\Model;

use Common\Classes\Db\DBAbstraction;
use Exception;
use PDO;
use PDOStatement;
use PDOException;

/**
 * Filename     : timesheet_stat.class.php
 * Language     : PHP v7.4
 * Date created : 25/01-2025, Ivan
 * Last modified: 25/01-2025, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright: Copyright (C) 2025 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * A model-class for timesheet-stat that handels access to data and attributes for the timesheet-statistics.
 */
class TimesheetStat
{
    const DB_TABLE_NAME ='timesheet';
    const DB_TABLE_ALIAS ='t';

    public function __construct() {
    }

    public function __destruct() {
    }

    public static function getInstance() : TimesheetStat {
        return new TimesheetStat();
    }

    /**
     * @param DBAbstraction $p_dbAbstraction
     * @param string $p_employeeUuid Default blank.
     * @param string $p_isoDateFrom ISO start-date of the period.
     * @param string $p_isoDateTo ISO end-date of the period.
     */
    public function retriveTimesheetStats(DBAbstraction $p_dbAbstraction,
                                          string $p_employeeUuid ='',
                                          string $p_isoDateFrom ='',
                                          string $p_isoDateTo ='') {
      // Retrive PDO database-connection.
      $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
      if (DBAbstraction::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
        // This super smart SQL-statement selects both the once not registered and the registered ones.
        $sql = 'WITH RECURSIVE m AS (';
        $sql .= PHP_EOL;
        $sql .= 'SELECT :period_from_date AS working_date';
        $sql .= ',QUARTER(:period_from_date) AS working_quarter';
        $sql .= ',YEARWEEK(:period_from_date) AS working_week';
        $sql .= ',:zero_regular AS total_hours_regular';
        $sql .= ',:zero_overtime AS total_hours_overtime';
        $sql .= ',:zero_break AS total_hours_break';
        $sql .= PHP_EOL;
        $sql .= 'UNION ALL';
        $sql .= PHP_EOL;
        $sql .= 'SELECT DATE_ADD(m.working_date, INTERVAL 1 DAY)';
        $sql .= ',QUARTER(m.working_date) AS working_quarter';
        $sql .= ',YEARWEEK(m.working_date) AS working_week';
        $sql .= ',:zero_regular AS total_hours_regular';
        $sql .= ',:zero_overtime AS total_hours_overtime';
        $sql .= ',:zero_break AS total_hours_break';
        $sql .= PHP_EOL;
        $sql .= 'FROM m';
        $sql .= PHP_EOL;
        $sql .= 'WHERE DATE_ADD(m.working_date, INTERVAL 1 DAY) <= :period_to_date';
        $sql .= PHP_EOL;
        $sql .= ')';

        $sql .= PHP_EOL;
        $sql .= 'SELECT DISTINCT working_quarter';
        $sql .= ',working_week';
        $sql .= ',total_hours_regular';
        $sql .= ',total_hours_overtime';
        $sql .= ',total_hours_break';
        $sql .= PHP_EOL;
        $sql .= 'FROM m';
        $sql .= PHP_EOL;

        $sql .= PHP_EOL;
        $sql .= 'UNION DISTINCT';
        $sql .= PHP_EOL;

        // Records that are acctually registered
        $sql .= sprintf('SELECT QUARTER(%s.timesheet_work_date) AS working_quarter', self::DB_TABLE_ALIAS);
        $sql .= sprintf(',YEARWEEK(%s.timesheet_work_date) AS working_week', self::DB_TABLE_ALIAS);
        $sql .= sprintf(',SUM(%s.timesheet_hours_regular) AS total_hours_regular', self::DB_TABLE_ALIAS);
        $sql .= sprintf(',SUM(%s.timesheet_hours_overtime) AS total_hours_overtime', self::DB_TABLE_ALIAS);
        $sql .= sprintf(',SUM(%s.timesheet_hours_break) AS total_hours_break', self::DB_TABLE_ALIAS);
        $sql .= PHP_EOL;
        $sql .= sprintf('FROM %s %s', self::DB_TABLE_NAME, self::DB_TABLE_ALIAS);
        $sql .= PHP_EOL;
        $sql .= sprintf('WHERE %s.employee_uuid = :employee_uuid', self::DB_TABLE_ALIAS);
        $sql .= PHP_EOL;
        $sql .= sprintf('AND %s.timesheet_work_date BETWEEN :period_from_date AND :period_to_date', self::DB_TABLE_ALIAS);
        $sql .= PHP_EOL;
        $sql .= 'GROUP BY working_week, working_quarter';
        $sql .= PHP_EOL;
        $sql .= 'ORDER BY working_week ASC';
        // $sql .= 'ORDER BY working_week ASC, working_quarter ASC';

        // Prepare and execute the SQL-statement.
        $pdoStatement = $dbPDOConnection->prepare($sql);
        if (!$pdoStatement) {
          trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
        } else {
          $zeroHours =0;
          //var_dump($pdoStatement);
          try {
            // Map parameters and execute.
            $pdoStatement->bindParam(':employee_uuid', $p_employeeUuid, PDO::PARAM_STR);
            $pdoStatement->bindParam(':period_from_date', $p_isoDateFrom, PDO::PARAM_STR);
            $pdoStatement->bindParam(':period_to_date', $p_isoDateTo, PDO::PARAM_STR);
            $pdoStatement->bindParam(':zero_regular', $zeroHours, PDO::PARAM_INT);
            $pdoStatement->bindParam(':zero_overtime', $zeroHours, PDO::PARAM_INT);
            $pdoStatement->bindParam(':zero_break', $zeroHours, PDO::PARAM_INT);

            // Run the SQL-statment against the database.
            $pdoStatement->execute();
            return $p_dbAbstraction->fetchAll_asAssocArray($pdoStatement);
          } catch (PDOException $e) {
            // Re-throw exception
            // throw new PDOException($e->getMessage(), $e->getCode());
            trigger_error($e->getMessage(), E_USER_ERROR);
            exit(1);
          }
        }
      }
    }

    /**
     * @param DBAbstraction $p_dbAbstraction
     * @param string $p_employeeUuid Default blank.
     * @param string $p_isoDateFrom ISO start-date of the period.
     * @param string $p_isoDateTo ISO end-date of the period.
     */
    public function retriveTimesheetStats_onlyRegistered(DBAbstraction $p_dbAbstraction,
                                          string $p_employeeUuid ='',
                                          string $p_isoDateFrom ='',
                                          string $p_isoDateTo ='') {
      // Retrive PDO database-connection.
      $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
      if (DBAbstraction::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
        // Records that are acctually registered
        $sql = sprintf('SELECT YEARWEEK(%s.timesheet_work_date) AS working_week', self::DB_TABLE_ALIAS);
        $sql .= sprintf(',QUARTER(%s.timesheet_work_date) AS working_quarter', self::DB_TABLE_ALIAS);
        $sql .= sprintf(',SUM(%s.timesheet_hours_regular) AS total_hours_regular', self::DB_TABLE_ALIAS);
        $sql .= sprintf(',SUM(%s.timesheet_hours_overtime) AS total_hours_overtime', self::DB_TABLE_ALIAS);
        $sql .= sprintf(',SUM(t.timesheet_hours_break) AS total_hours_break', self::DB_TABLE_ALIAS);
        $sql .= PHP_EOL;
        $sql .= sprintf('FROM %s %s', self::DB_TABLE_NAME, self::DB_TABLE_ALIAS);
        $sql .= PHP_EOL;
        $sql .= sprintf('WHERE %s.employee_uuid = :employee_uuid', self::DB_TABLE_ALIAS);
        $sql .= PHP_EOL;
        $sql .= sprintf('AND %s.timesheet_work_date BETWEEN :period_from_date AND :period_to_date', self::DB_TABLE_ALIAS);
        $sql .= PHP_EOL;
        $sql .= 'GROUP BY working_week, working_quarter';
        $sql .= PHP_EOL;
        $sql .= 'ORDER BY working_week ASC';

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
            return $p_dbAbstraction->fetchAll_asAssocArray($pdoStatement);
          } catch (PDOException $e) {
            // Re-throw exception
            // throw new PDOException($e->getMessage(), $e->getCode());
            trigger_error($e->getMessage(), E_USER_ERROR);
            exit(1);
          }
        }
      }
    }
}