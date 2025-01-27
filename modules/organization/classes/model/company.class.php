<?php
namespace App\Modules\Organization\Classes\Model;

use Exception;
use Common\Classes\Db\DBAbstraction;
use Common\Classes\Model\StdModel;
use Common\Classes\Model\SaveableObjectInterface;
use PDO;
use PDOStatement;
use PDOException;
use UnexpectedValueException;

/**
 * Filename     : company.class.php
 * Language     : PHP v7.4
 * Date created : 12/11-2022, Ivan
 * Last modified: 12/11-2022, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2022 by Ivan Mark Andersen
 *
 * Description:
 *  A company model-class that handels access to data and attributes and persisting data into the database.
 */
class Company extends StdModel implements SaveableObjectInterface
{
    const db_table_name = 'company';

    public function __construct() {
        // Initalize the super-class of the instance.
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

}