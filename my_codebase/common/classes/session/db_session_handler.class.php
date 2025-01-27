<?php
namespace Common\Classes\Session;

use Common\Classes\Session\StandardSessionHandler;
use Common\Classes\Db\DBAbstraction;
/*
use SessionUpdateTimestampHandlerInterface;
*/
use SessionHandlerInterface;
use PDO;
use PDOStatement;
use PDOException;
use Exception;

/**
 * Filename     : DBSessionHandler
 * @version     : v1.5.4
 * Date created : 11/05-2023
 * Date modified: 10/04-2024
 * @author      : Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2023 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * This class wraps native PHP session-handler operations on session-data using a database and PDO-statements.
 * Sessions are temporary storage-areas, which make it possible to maintain a state in a state-less environment like the Web.
 * The data stored in a session persists across different web-pages on the same web-site.
 *
 * NOTE:
 * session_start() calls open method and then the read-method and returns true for open
 * and the value of session or empty for read.
 *
 * @see: https://www.php.net/manual/en/session.configuration.php
 * @see: https://code-boxx.com/save-php-session-in-database/
 */
class DBSessionHandler extends StandardSessionHandler implements SessionHandlerInterface /*, SessionUpdateTimestampHandlerInterface */
{
  const DB_TABLE_NAME = 'sessions';
  const DB_TABLE_ALIAS = 's';
  const DB_TABLE_COLUMNS = 'session_id,session_data,session_expires';

  /**
   * @var DBAbstraction
   */
  private $dbAbstraction;

  /**
   * The class constructor.
   * @access public
   * 
   * @param DBAbstraction $p_dbAbstraction
   * @param int $p_sessionExpiresInSecs Default expire-time is 5400 seconds.
   * @param string $p_savePath Default blank.
   * @param string $p_sessionName Default session-name 'PHPSESSID'
   */
  public function __construct(DBAbstraction &$p_dbAbstraction,
                              int $p_sessionExpiresInSecs =self::SESS_LIFETIME_DEFAULT,
                              string $p_savePath ='',
                              string $p_sessionName ='PHPSESSID') {
    parent::__construct($p_sessionExpiresInSecs, $p_savePath, $p_sessionName);
    $this->setDBAbstraction($p_dbAbstraction);

    // Set save-handlers
    session_set_save_handler(array($this,'open'),
                             array($this,'close'),
                             array($this,'read'),
                             array($this,'write'),
                             array($this,'destroy'),
                             array($this,'gc'));

    // Register a shutdown-handler for the session-handler.
    // register_shutdown_function(array($this,'sessionShutdown'));
  }

  public function __destruct() {
    parent::__destruct();
  }

  /**
   * @param DBAbstraction $p_dbAbstraction
   * @param int $p_sessionExpiresInSecs Default 5400.
   * @param string $p_sessionName Default 'PHPSESSID'
   * @return DBSessionHandler
   */
  public static function getInstance(DBAbstraction $p_dbAbstraction,
                                     int $p_sessionExpiresInSecs =self::SESS_LIFETIME_DEFAULT,
                                     string $p_savePath ='',
                                     string $p_sessionName ='PHPSESSID') : DBSessionHandler {
    return new DBSessionHandler($p_dbAbstraction, $p_sessionExpiresInSecs, $p_savePath, $p_sessionName);
  }

  protected function setDBAbstraction(DBAbstraction $p_dbAbstraction) : void {
    $this->dbAbstraction = $p_dbAbstraction;
  }

  protected function getDBAbstraction() : DBAbstraction {
    return $this->dbAbstraction;
  }

  public static function getArrayOfColumns() : array {
    return explode(',', self::DB_TABLE_COLUMNS);
  }

  /**
   * Starts a session using cookie-based sessions.
   * NOTE: session_start() must be called before outputing anything to the browser.
   * @see: https://www.php.net/manual/en/function.session-start.php
   * @see: https://www.php.net/manual/en/session.idpassing.php
   * @access public
   * @param string $p_resumeSessId Default blank.
   * @return bool
   */
  public function start(string $p_resumeSessId ='') : bool {
    if (!$this->hasExistingSession()) {
      // Set session-id
      $newSessionId = $this->generateSessionId();
      $this->setID($newSessionId);

      // $this->setCookieParms($this->session_options);
      $this->sendSessionCookie($newSessionId);
      // We need to call session_start to be able to access the _SESSION variable.
      // Use the @ at the beginning to suppress the PHP notice about it has allready been started.
      @session_start();
      return TRUE;
    } else {
      // Set session-id
      if (isset($p_resumeSessId) && !empty($p_resumeSessId)) {
        $this->setID($p_resumeSessId);
      } elseif (isset($_COOKIE['PHPSESSID']) && !empty($_COOKIE['PHPSESSID'])) {
        $this->setID(substr(preg_replace('/[^a-z0-9]/', '', $_COOKIE['PHPSESSID']), 0, 26));
      } else {
        $this->setID(session_id());
      }

      // Start session
      if (!isset($_SESSION)) {
        @session_start();
      }

      return FALSE;
    }
  }

  /* Session interface Methods */

  /**
   * Generates a collision-free session-id.
   * @return string
   */
  public function generateSessionId() : string {
    return session_create_id();
  }

  /**
   * Re-initialize existing session, or creates a new one.
   * This mehtod is called when a session starts or when session_start() is invoked.
   * 
   * @param string $p_sessionPath Default '/'
   * @param string $p_sessionName Default 'PHPSESSID'
   * @return bool
   */
  public function open($p_sessionPath ='/',
                       $p_sessionName ='PHPSESSID') : bool {
    if (!$this->hasExistingSession()) {
      $this->setName($p_sessionName);
      return TRUE;
    } else {
      return TRUE;
    }
  }

  /**
   * @return bool
   */
  public function close() : bool {
    // Write session-data and end the session.
    // return session_write_close();
    return TRUE;
  }

  /**
   * Reads a value of a given session-entry.
   * @param string $p_key
   * @return string|bool
   * @throws Exception
   */
  public function read($p_key) : string {
    $dbAbstraction = $this->getDBAbstraction();
    $dbPDOConnection = $dbAbstraction->getPDOConnectionInstance();
    if (DBAbstraction::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
      // Setup the SQL-statement using PDO.
      $sql = 'SELECT session_data';
      $sql .= PHP_EOL;
      $sql .= sprintf('FROM %s', self::DB_TABLE_NAME);
      $sql .= PHP_EOL;
      $sql .= 'WHERE session_id = :sess_id';
      $sql .= PHP_EOL;
      $sql .= 'AND session_expires >= CURRENT_TIMESTAMP';

      // Prepare and execute the SQL-statement.
      $pdoStatement = $dbPDOConnection->prepare($sql);
      if (!$pdoStatement) {
        // Driver-specific error-code and error-message.
        $arrErrorInfo = $dbPDOConnection->errorInfo();
        Throw new Exception(sprintf('Unable to prepare the SQL-statement. Error-code: %s, Error message: %s', $arrErrorInfo[1], $arrErrorInfo[2]));
      } else {
        try {
          // Map parameters and execute.
          $pdoStatement->bindParam(':sess_id', $p_key, PDO::PARAM_STR);
          $wasSuccessful = $pdoStatement->execute();
          if ($wasSuccessful) {
            $arrRowAssoc = $dbAbstraction->fetchRow_asAssocArray($pdoStatement);
            if ($arrRowAssoc) {
              return $arrRowAssoc['session_data'];
            } else {
              return FALSE;
            }
          } else {
            return FALSE;
          }
        } catch (PDOException $e) {
          throw new Exception($e->getMessage(), $e->getCode());
        }
      }
    } else {
      throw new Exception('No active database-connection available ...');
    }
  }

  /**
   * Checks if a given record exists.
   * @param string $p_sessionId
   * @return bool
  */
  public function doesExists(string $p_sessionId) : bool {
    $dbPDOConnection = $this->dbAbstraction->getPDOConnectionInstance();
    if (DBAbstraction::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
      // Setup the SQL-statement.
      $sql = 'SELECT count(session_id) AS NUM_RECORDS_FOUND';
      $sql .= PHP_EOL;
      $sql .= sprintf('FROM %s', self::DB_TABLE_NAME);
      $sql .= PHP_EOL;
      $sql .= 'WHERE session_id = :sess_id';
      $sql .= PHP_EOL;
      $sql .= 'LIMIT 1';

      // Prepare and execute the SQL-statement.
      $pdoStatement = $dbPDOConnection->prepare($sql);
      if (!$pdoStatement) {
        // Driver-specific error-code and error-message.
        $arrErrorInfo = $dbPDOConnection->errorInfo();
        throw new Exception(sprintf('Unable to prepare the SQL-statement. Error-code: %s, Error message: %s', $arrErrorInfo[1], $arrErrorInfo[2]));
      } else {
        try {
          // Map parameters
          $pdoStatement->bindParam(':sess_id', $p_sessionId, PDO::PARAM_STR);
          // Execute and return the boolean-result.
          return $this->dbAbstraction->fetchBooleanResult($pdoStatement);
        } catch (PDOException $e) {
          // Re-throw the exception to an higher level.
          throw new Exception($e->getMessage(), $e->getCode());
        }
      }
    } else {
      throw new Exception('No active database-connection available ...');
    }
  }

  /**
   * @param string $p_sessId
   * @param string $p_sessData
   * @return bool
   * @throws Exception
   */
  public function write($p_sessId, $p_sessData): bool {
    $dbPDOConnection = $this->dbAbstraction->getPDOConnectionInstance();
    if (DBAbstraction::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
      if (!$this->doesExists($p_sessId)) {
        $arrCols = self::getArrayOfColumns();
        // Setup INSERT SQL-statement.
        $sql = sprintf('INSERT INTO %s', self::DB_TABLE_NAME);
        $sql .= PHP_EOL;

        // Add dynamic list of columns.
        foreach ($arrCols as $idx => $curCol) {
          if ($idx ==0) {
            $sql .= sprintf('(%s', $curCol);
          } else {
            $sql .= sprintf(',%s', $curCol);
          }
        }
        $sql .= ')';
        $sql .= PHP_EOL;
        $sql .= 'VALUES(';
        $sql .= PHP_EOL;
        $sql .= ':sess_id';
        $sql .= PHP_EOL;
        $sql .= ',:sess_data';
        $sql .= PHP_EOL;
        $sql .= ',CURRENT_TIMESTAMP + INTERVAL :sess_lifetime SECOND)';

        // Prepare and execute the SQL-statement.
        $pdoStatement = $dbPDOConnection->prepare($sql);
        if (!$pdoStatement) {
          // Driver-specific error-code and error-message.
          $arrErrorInfo = $dbPDOConnection->errorInfo();
          Throw new Exception(sprintf('Unable to prepare the SQL-statement. Error-code: %s, Error message: %s', $arrErrorInfo[1], $arrErrorInfo[2]));
        } else {
          $sessLifetime = $this->getSessionLifetime();
          try {
            // Map parameters
            $pdoStatement->bindParam(':sess_id', $p_sessId, PDO::PARAM_STR);
            $pdoStatement->bindParam(':sess_data', $p_sessData, PDO::PARAM_STR);
            $pdoStatement->bindParam(':sess_lifetime', $sessLifetime, PDO::PARAM_INT);
            // Execute a prepaired SQL-statment.
            return $pdoStatement->execute();
          } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
          }
        }
      } else {
        // Setup UPDATE SQL-statement.
        $sql = sprintf('UPDATE %s', self::DB_TABLE_NAME);
        $sql .= PHP_EOL;
        $sql .= 'SET session_data = :sess_data';
        $sql .= PHP_EOL;
        $sql .= 'WHERE session_id = :sess_id';

        // Prepare and execute the SQL-statement.
        $pdoStatement = $dbPDOConnection->prepare($sql);
        if (!$pdoStatement) {
          // Driver-specific error-code and error-message.
          $arrErrorInfo = $dbPDOConnection->errorInfo();
          throw new Exception(sprintf('Unable to prepare the SQL-statement. Error-code: %s, Error message: %s', $arrErrorInfo[1], $arrErrorInfo[2]));
        } else {
          try {
            // Map parameters
            $pdoStatement->bindParam(':sess_id', $p_sessId, PDO::PARAM_STR);
            $pdoStatement->bindParam(':sess_data', $p_sessData, PDO::PARAM_STR);
            // Execute a prepaired SQL-statment.
            return $pdoStatement->execute();
            // return TRUE;
          } catch (PDOException $e) {
            // Re-throw
            throw new Exception($e->getMessage(), $e->getCode());
          }
        }
      }
    } else {
      throw new Exception('No active database-connection available ...');
    }
  }

  /**
   * Destroys the given session.
   * @param string $p_sessId
   * @return bool
   */
  public function destroy($p_sessId) : bool {
    $dbPDOConnection = $this->dbAbstraction->getPDOConnectionInstance();
    if (DBAbstraction::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
      // Setup the SQL-statement.
      $sql = sprintf('DELETE FROM %s', self::DB_TABLE_NAME);
      $sql .= PHP_EOL;
      $sql .= 'WHERE session_id = :sess_id';

      // Prepare and execute the SQL-statement.
      $pdoStatement = $dbPDOConnection->prepare($sql);
      if (!$pdoStatement) {
        // Driver-specific error-code and error-message.
        $arrErrorInfo = $dbPDOConnection->errorInfo();
        throw new Exception(sprintf('Unable to prepare the SQL-statement. Error-code: %s, Error message: %s', $arrErrorInfo[1], $arrErrorInfo[2]));
      } else {
        try {
          // Map parameters
          $pdoStatement->bindParam(':sess_id', $p_sessId, PDO::PARAM_STR);
          // Execute a prepaired SQL-statment.
          return $pdoStatement->execute();
        } catch (PDOException $e) {
          // Re-throws the exception
          throw new Exception($e->getMessage());
        }
      }
    } else {
      throw new Exception('No active database-connection available ...');
    }
  }

  /**
   * Session garbage-collector method that removes all sessions that has expired.
   * @param int $p_maxLifetime Number of seconds in which a session can maximum live.
   * @return int|bool
   * @throws Exception
   */
  public function gc($p_maxLifetime =self::SESS_LIFETIME_DEFAULT) : int {
    $dbPDOConnection = $this->dbAbstraction->getPDOConnectionInstance();
    if (DBAbstraction::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
      // Setup SQL-statement.
      $sql = sprintf('DELETE FROM %s', self::DB_TABLE_NAME);
      $sql .= PHP_EOL;
      $sql .= 'WHERE DATE_ADD(session_expires,INTERVAL :sess_lifetime_negative SECOND) < CURRENT_TIMESTAMP';

      // Prepare and execute the SQL-statement.
      $pdoStatement = $dbPDOConnection->prepare($sql);
      if (!$pdoStatement) {
        // Driver-specific error-code and error-message.
        $arrErrorInfo = $dbPDOConnection->errorInfo();
        throw new Exception(sprintf('Unable to prepare the SQL-statement. Error-code: %s, Error message: %s', $arrErrorInfo[1], $arrErrorInfo[2]));
      } else {
        // Make it positive in case if number is negative
        $negativeSessionLifetime = abs($p_maxLifetime) * -1;
        try {
          // Map parameters
          $pdoStatement->bindParam(':sess_lifetime_negative', $negativeSessionLifetime, PDO::PARAM_INT);
          // Execute a prepaired SQL-statment.
          return $pdoStatement->execute();
        } catch (PDOException $e) {
          throw new Exception($e->getMessage(), $e->getCode());
        }
      }
    } else {
      throw new Exception('No active database-connection available ...');
    }
  }

  /**
   * Updates the session life-time of an existing session.
   * @param string $p_sessionId
   * @param string $p_sessionData
   * @return bool
   * @throws Exception
   */
  public function updateTimestamp($p_sessionId, $p_sessionData): bool {
    $dbPDOConnection = $this->dbAbstraction->getPDOConnectionInstance();
    if (DBAbstraction::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
      // Setup SQL-statement.
      $sql = sprintf('UPDATE %s', self::DB_TABLE_NAME);
      $sql .= PHP_EOL;
      $sql .= 'SET session_data = :sess_data';
      $sql .= PHP_EOL;
      $sql .= ',session_expires = CURRENT_TIMESTAMP + INTERVAL :sess_lifetime SECOND';
      $sql .= PHP_EOL;
      $sql .= 'WHERE session_id = :sess_id';

      // Prepare and execute the SQL-statement.
      $pdoStatement = $dbPDOConnection->prepare($sql);
      if (!$pdoStatement) {
        // Driver-specific error-code and error-message.
        $arrErrorInfo = $dbPDOConnection->errorInfo();
        throw new Exception(sprintf('Unable to prepare the SQL-statement. Error-code: %s, Error message: %s', $arrErrorInfo[1], $arrErrorInfo[2]));
      } else {
        $sessLifetime = $this->getSessionLifetime();
        try {
          // Map parameters
          $pdoStatement->bindParam(':sess_id', $p_sessionId, PDO::PARAM_STR);
          $pdoStatement->bindParam(':sess_data', $p_sessionData, PDO::PARAM_STR);
          $pdoStatement->bindParam(':sess_lifetime', $sessLifetime, PDO::PARAM_INT);
          // Execute a prepaired SQL-statment.
          return $pdoStatement->execute();
        } catch (PDOException $e) {
          throw new Exception($e->getMessage(), $e->getCode());
        }
      }
    } else {
      throw new Exception('No active database-connection available ...');
    }
  }
/*
  public function sessionShutdown() {
    // Some custom clean-up, logging and so on
    // echo __METHOD__.': TEST setting up how-to and when to shutdown ...';
    session_set_save_handler($this, FALSE);
  }
*/
} // End class