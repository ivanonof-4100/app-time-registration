<?php
namespace Common\Classes\Db;

use Common\Classes\FileHandler;
use Common\Classes\Datetime\CustomDateTime;
use PDO;
use PDOException;
use PDOStatement;

class DBAbstraction
{
  // Attributes
  /**
   * @var string
   */
  protected $dbHost;

  /**
   * @var string
   */
  protected $dbName;

  /**
   * @var string
   */
  protected $dbCodepage;

  /**
   * @var PDO
   */
  protected $dbConnection;

  /**
   * Default constructor
   */
  public function __construct() {
  }

  public function __destruct() {
  }

  protected function __clone() {
    trigger_error('It is NOT allowed to clone the instance handling the database-connection ...', E_USER_ERROR);
  }

  /**
   * @param string $p_dbHost
   * @return void
   */
  protected function setAttr_dbHost(string $p_dbHost) : void {
    $this->dbHost = (string) $p_dbHost;
  }
    
  /**
   * @return string
   */
  protected function getAttr_dbHost() : string {
    return $this->dbHost;
  }
    
  /**
   * @param string $p_dbName
   */
  protected function setAttr_dbName(string $p_dbName) : void {
    $this->dbName = (string) $p_dbName;
  }

  /**
   * @return string
   */
  protected function getAttr_dbName() : string {
    return $this->dbName;
  }
    
  /**
   * @param string $p_dbCodepage Default 'utf8mb4'.
   */
  protected function setAttr_dbCodepage(string $p_dbCodepage ='utf8mb4') : void {
    $this->dbCodepage = (string) $p_dbCodepage;
  }

  protected function getAttr_dbCodepage() : string {
    return $this->dbCodepage;
  }

  /**
   * @param string $p_errorMesg
   * @return void
   */
  protected static function logError($p_errorMesg ='') : void {
    // Log occured error
    $customDateTimeObj = CustomDateTime::getInstance();
    $logEntry = sprintf('%s, Error: %s'. PHP_EOL, $customDateTimeObj->getFormatedDatetime(), $p_errorMesg);
    $logFile = APP_LOG_PATH .sprintf('%s.log', 'errors.pdo-mysql');
    $fileHandler = FileHandler::getInstance();
    $fileHandler->appendToFile($logFile, $logEntry);
  }

  /**
   * @return string
   */
  public static function getFormat_ofDateTime() : string {
    return 'Y-m-d H:i:s';
  }

  /**
   * @return string
   */
  public static function getFormat_ofDate() : string {
    return 'Y-m-d';
  }

  /**
   * @param resource $p_dbPDOConnection
   * @return bool
   */
  public static function doesDatabaseConnection_meetCriterias($p_dbPDOConnection) : bool {
    return (is_object($p_dbPDOConnection) && ($p_dbPDOConnection instanceof PDO));
  }

  /**
   * Enables auto-commit for transactions.
   * @return void
   */
  public function enableAutoCommit(PDO $p_dbPDOConnection) : void {
    $p_dbPDOConnection->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
  }
  
  /**
   * Disables auto-commit for transactions.
   * @return void
   */
  public function disableAutoCommit(PDO $p_dbPDOConnection) : void {
    $p_dbPDOConnection->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
  }

  /**
   * @param string $p_pdoModule Default 'mysql'
   * @return bool
   */
  public static function isRequiredPDODriverInstalled(string $p_pdoModule ='mysql') : bool {
    $arrInstalledDrivers = PDO::getAvailableDrivers();
    if (empty($arrInstalledDrivers)) {
      return FALSE;
    } else {
      return in_array($p_pdoModule, $arrInstalledDrivers, TRUE);
    }
  }

  /**
   * @param resource $pbr_dbConnection
   * @return void
   */
  protected function setAttr_dbConnection(&$pbr_dbConnection) : void {
    $this->dbConnection = $pbr_dbConnection;
  }

  /**
   * @return PDO
   */
  public function getPDOConnectionInstance() : PDO {
    return $this->dbConnection;
  }

  /**
   * @return PDO
   */
  public function getAttr_dbConnection() : PDO {
    return $this->dbConnection;
  }

  public function enableLocalInFile(PDO $p_dbPDOConnection) : void {
    $p_dbPDOConnection->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, 1);
  }

  /**
   * @return bool
   */
  public function beginTransaction() : bool {
    $dbPDOConnection = $this->getAttr_dbConnection();
    // First explicitly disable auto-commit to initiate the transaction.
    $this->disableAutoCommit($dbPDOConnection);
    return $dbPDOConnection->beginTransaction();
  }

  /**
   * Commits the current transaction.
   * @return bool
   */
  public function commit() : bool {
    $dbPDOConnection = $this->getAttr_dbConnection();
    return $dbPDOConnection->commit();
/*
    // Go back to auto-commit again.
    $this->enableAutoCommit($dbPDOConnection);
*/
  }

  /**
   * Does a rollback on the current database-transaction.
   * @return bool
   */
  public function rollback() : bool {
    $dbPDOConnection = $this->getAttr_dbConnection();
    return $dbPDOConnection->rollBack();
  }

   /**
    * Execute an SQL statement and return the number of affected rows.
    * 
    * @param PDO $p_dbPDOConnection
    * @param string $p_sqlStatement
    * @return int
    * @todo Ivan: This is maybe NOT used - this method look wired!
    */
    public function execute(PDO $p_dbPDOConnection, string $p_sqlStatement) : int {
       try {
         return $p_dbPDOConnection->exec($p_sqlStatement);
       } catch (PDOException $e) {
         self::logError($e->getMessage());
       }
    }
 
    /**
     * @param PDOStatement $p_pdoStatementObj
     * @return bool
     */
    public function fetchBooleanResult(PDOStatement $p_pdoStatementObj) : bool {
       try {
         $p_pdoStatementObj->execute();
         $dbResultSet = $p_pdoStatementObj->fetch(PDO::FETCH_NUM);
         $p_pdoStatementObj->closeCursor();
 
         return (boolean) $dbResultSet[0];
       } catch (PDOException $e) {
         self::logError($e->getMessage());
       }
    }

    /**
     * @param PDOStatement $p_pdoStatement
     * @return mixed
     */
    public function fetchFirstColumn(PDOStatement $p_pdoStatementObj) {
       try {
         $p_pdoStatementObj->execute();
         $dbResultSet = $p_pdoStatementObj->fetchColumn(0);
         $p_pdoStatementObj->closeCursor();

         return $dbResultSet;
       } catch (PDOException $e) {
         self::logError($e->getMessage());
      }
    }

    /**
     * @param PDOStatement $p_pdoStatement
     * @param int $p_idxX
     * 
     * @return mixed
     */
    public function fetchColumnX(PDOStatement $p_pdoStatement, $p_idxX =0) {
        try {
            $p_pdoStatement->execute();
            $dbResultSet = $p_pdoStatement->fetchColumn($p_idxX);
            $p_pdoStatement->closeCursor();
            return $dbResultSet;
        } catch (PDOException $e) {
            self::logError($e->getMessage());
        }
    }

    public function fetchRow_asAssocArray(PDOStatement $p_pdoStatement) {
        try {
            $p_pdoStatement->execute();
            return $p_pdoStatement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            self::logError($e->getMessage());
        }
    }

    public function fetchAll_asAssocArray(PDOStatement $p_pdoStatement) : array {
        try {
            // Run fetch-loop
            do {
                $arrResult = $p_pdoStatement->fetchAll(PDO::FETCH_ASSOC);
            } while ($p_pdoStatement->nextRowset());

            $p_pdoStatement->closeCursor();
            return $arrResult;
        } catch (PDOException $e) {
            self::logError($e->getMessage());
        }
    }

    public function fetchAll_asObjectArray(PDOStatement $p_pdoStatement, $p_className) : array {
        try {
            do {
                // Run fetch-loop
                $arrRowAssoc = $this->fetchRow_asAssocArray($p_pdoStatement);
                // $p_pdoStatement->setFetchMode(PDO::FETCH_CLASS, $p_className, $arrRowAssoc);
                $p_pdoStatement->setFetchMode(PDO::FETCH_CLASS, $p_className, $arrRowAssoc);
                
                $arrResult = $p_pdoStatement->fetchAll(PDO::FETCH_CLASS);
            } while ($p_pdoStatement->nextRowset());

            $wasSuccessful = $p_pdoStatement->closeCursor();
            if ($wasSuccessful) {
              return $arrResult;
            }            
        } catch (PDOException $e) {
            self::logError($e->getMessage());
        }
    }

    public function fetchObject(PDOStatement $p_pdoStatement, $p_className ='StdClass') {
        try {
            $arrRowAssoc = $this->fetchRow_asAssocArray($p_pdoStatement);
            $initalizedObj = $p_pdoStatement->setFetchMode(PDO::FETCH_CLASS, $p_className, $arrRowAssoc);
            $p_pdoStatement->closeCursor();
            return $initalizedObj;
        } catch (PDOException $e) {
            self::logError($e->getMessage());
        }
    }

    /**
     * @param PDOStatement $p_pdoStatement
     * @param int|boolean $p_columnIndex Default FALSE.
     * 
     * @return array[value]
     */
    public function fetchRows_asSingleColumnArray(PDOStatement $p_pdoStatement, $p_columnIndex =FALSE) {
        try {
            $p_pdoStatement->execute();
            if ($p_columnIndex !== FALSE) {
              $arrSingleColData = $p_pdoStatement->fetchAll(PDO::FETCH_COLUMN, $p_columnIndex);
            } else {
              // Choosen column-index was ommited.
              $arrSingleColData = $p_pdoStatement->fetchAll(PDO::FETCH_COLUMN);
            }
            return $arrSingleColData;
        } catch (PDOException $e) {
            self::logError($e->getMessage());
        }
    }
} // End class