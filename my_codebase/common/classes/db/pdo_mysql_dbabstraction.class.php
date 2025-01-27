<?php
namespace Common\Classes\Db;

use Common\Classes\Db\DBAbstraction;
use PDO;
use PDOStatement;
use PDOException;
use Exception;

/**
 * Filename     : pdo_mysql_dbabstraction.class.php
 * Language     : PHP v7.4+, 7.2
 * Date created : 21/01-2014, IMA
 * Last modified: 18/05-2023, IMA
 * Developers   : @author IMA Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2023 by Ivan Mark Andersen
 *
 * Description:
 * My database-abstraction class for MySQL RDBMS using PHP Data Objects (PDO).
 *
 * For more information on PDO you should read the online PHP-manual on the topic.
 * URL: http://www.php.net/manual/en/book.pdo.php
 *
 * @example:
 * // Get PDO-database-connection.
 * $mySQLDBAbstraction = MySQLDBAbstraction::getInstance('localhost', 'dbname', 'utf8mb4');
 * $dbPDOConnection = $mySQLDBAbstraction->initDatabaseConnection('dbUser', 'dbPass');
 */
class MySQLDBAbstraction extends DBAbstraction
{
  const PORT_MYSQL = 3306;

   /**
    * Default constructor of the class.
    *
    * @param string $p_dbHost
    * @param string $p_dbName
    * @param string $p_dbCodepage Default 'utf8mb4'
    */
   public function __construct($p_dbHost, $p_dbName, $p_dbCodepage ='utf8mb4') {
      parent::__construct();
      $this->setAttr_dbHost($p_dbHost);
      $this->setAttr_dbName($p_dbName);
      $this->setAttr_dbCodepage($p_dbCodepage);
   }

   public function __destruct() {
      $this->disconnect();
      parent::__destruct();
   }

   protected function __clone() {
      trigger_error('It is NOT allowed to clone the instance handling the database-connection ...', E_USER_ERROR);
   }

   /**
    * @param string $p_dbHost
    * @param string $p_dbName
    * @param string $p_dbCodepage Default 'utf8mb4'
    * @param bool $p_dbCacheBuffers Default boolean FALSE.
    * @return MySQLDBAbstraction
    */
   public static function getInstance(string $p_dbHost, string $p_dbName, string $p_dbCodepage ='utf8mb4', bool $p_dbCacheBuffers =FALSE) : MySQLDBAbstraction {
      return new MySQLDBAbstraction($p_dbHost, $p_dbName, $p_dbCodepage, $p_dbCacheBuffers);
   }

   // Service methods

   /**
    * @param string $p_userName
    * @param string $p_userPasswd
    * @return PDO
    * @throws Exception
    */
   public function initDatabaseConnection(string $p_userName, string $p_userPasswd) : void {
      try {
        if (!self::isRequiredPDODriverInstalled('mysql')) {
          Throw new Exception('Required PHP PDO-module for handling MySQL connections is NOT installed.', 1);
        } else {
          try {
            // Connect to the database using database-abstraction.
            $pdoDBConnection = $this->connect($p_userName, $p_userPasswd);
            $this->setAttr_dbConnection($pdoDBConnection);
          } catch (Exception $e) {
            self::logError($e->getMessage());
            exit(2);
          }
        }
      } catch (Exception $e) {
        // Re-throws the exception.
        Throw new Exception($e->getMessage(), 1);
      }
   }

   /**
    * Checks to see if the RDBMS is up and running.
    * @return bool Boolean-value that tell whether or not the RDBMS is running.
    */
   public static function isRDBMSRunning() : bool {
      // System-command that determines whether or not the RDBMS is running.
      $systemCommand = 'ps -u mysql|grep mysqld';
      $arrOutput = array();
      $lastLineOfOutput = exec($systemCommand, $arrOutput, $pbr_exitCode);
      if ($pbr_exitCode == 0) {
        // Selected line was found => the server-process is running.
        $isRunning = true;
      } else {
        // Selected line was NOT found => the server-process is not running.
        $isRunning = false;
        if ($pbr_exitCode == 1) {
          // Selected line was not found.
          self::logError('The MySQL RDBMS is NOT running!');
        } elseif ($pbr_exitCode >= 2) {
          // An error has occured ...
          self::logError('An error has occured. We actually dont know, if the RDBMS-server is running or not, so we assume that it is not running ...');
        }
      }

      return $isRunning;
   }

  /**
   * Connects to the database specifyed when creating the instance.
   *
   * @param string $p_dbUsrName
   * @param string $p_dbUsrPasswd
   *
   * @throws PDOException
   * @return PDO
   */
  public function connect(string $p_dbUsrName, string $p_dbUsrPasswd) : PDO {
    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;', $this->getAttr_dbHost(), self::PORT_MYSQL, $this->getAttr_dbName());
    $arrOptions = array(PDO::MYSQL_ATTR_LOCAL_INFILE =>TRUE, PDO::MYSQL_ATTR_INIT_COMMAND => sprintf('SET NAMES %s', $this->getAttr_dbCodepage()));
    try {
      $dbPDOConnection = new PDO($dsn, $p_dbUsrName, $p_dbUsrPasswd, $arrOptions);
      if (!empty($dbPDOConnection) && ($dbPDOConnection instanceof PDO)) {
        // Use PDOExceptions, if any errors
        $dbPDOConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbPDOConnection->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $dbPDOConnection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // Enable things
        $this->useBufferedResult($dbPDOConnection);
        $this->enableAutoCommit($dbPDOConnection);

        return $dbPDOConnection;
      } else {
        self::logError('The instance of the database-connection is NOT of the right type ...');
        exit(4);
      }
    } catch (PDOException $e) {
      // Log occured error
      self::logError($e->getMessage());
      exit(3);
    }
  }

  public function disconnect() : void {
    unset($this->dbConnection);
  }

  /**
   * @param PDO $p_pdoConnection
   * @return void
   */
  public function useBufferedResult(PDO $p_pdoConnection) : void {
    $p_pdoConnection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
  }

  /**
   * @param PDO $p_pdoConnection
   * @return void
   */
  public function useUnbufferedResult(PDO $p_pdoConnection) : void {
    $p_pdoConnection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, FALSE);
  }

  /**
   * @param string $p_str Default blank.
   * @return string
   */
  public function getQuotedString(string $p_str ='') : string {
    $dbPDOConnection = $this->getAttr_dbConnection();
    return $dbPDOConnection->quote($p_str, PDO::PARAM_STR);
  }

  /**
   * Checks if a given table-name exists in the database.
   *
   * @param string $p_baseTableName Default blank.
   * @return bool
   */
  public function doesBasetableExists(string $p_baseTableName ='') : bool {
    // Setup the SQL-statement
    $sql = 'SELECT count(TABLE_NAME) AS NUM_RECORDS_FOUND';
    $sql .= PHP_EOL;
    $sql .= 'FROM information_schema.tables t';
    $sql .= PHP_EOL;
    $sql .= 'WHERE t.TABLE_NAME = :base_table_name';
    $sql .= PHP_EOL;
    $sql .= 'AND t.table_schema = :db_name';
    $sql .= PHP_EOL;
    $sql .= 'LIMIT 1';

    // Prepare and execute the SQL-statement.
    $dbPDOConnection = $this->getAttr_dbConnection();
    $pdoStatement = $dbPDOConnection->prepare($sql);
    if (!$pdoStatement) {
      trigger_error(__METHOD__ .': Uable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
    } else {
      try {
        $baseTableName = $dbPDOConnection->quote($p_baseTableName, PDO::PARAM_STR);
        $dbName = $dbPDOConnection->quote($this->getAttr_dbName(), PDO::PARAM_STR);

        // Map parameters and execute the SQL-statement.
        $pdoStatement->bindParam(':base_table_name', $baseTableName, PDO::PARAM_STR);
        $pdoStatement->bindParam(':db_name', $dbName, PDO::PARAM_STR);
        return $this->fetchBooleanResult($pdoStatement);
      } catch (PDOException $e) {
        self::logError($e->getMessage());
      }
    }
  }
} // End class