<?php
namespace Common\Classes\Db;
use Common\Classes\Db\MySQLDBAbstraction;

class DBAbstractionFactory
{
    const DEFAULT_DB_DRIVER = 'mysql';

    public function __construct() {
    }

    public function __destruct() {
    }

    public static function getInstance() : DBAbstractionFactory {
        return new DBAbstractionFactory();
    }

    public static function getSuited_dbAbstractionInstance($p_pdoDriver =self::DEFAULT_DB_DRIVER) {
        // Switch over supported database-abstractions.
        switch ($p_pdoDriver) {
          case "mysql": {
            // return new Session\Driver\Server($this->getOptions());
            break;
        } default: {

            break;
        }
    }
  }
} // End class