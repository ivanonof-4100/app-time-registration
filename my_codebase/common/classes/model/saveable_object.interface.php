<?php
namespace Common\Classes\Model;
use Common\Classes\Db\DBAbstraction;

/**
 * An object-interface that enforces the implentation of some important methods in the classes that implemnts the interface.
 */
interface SaveableObjectInterface
{
   /**
    * Inserts the objects data in the database.
    * @param DBAbstraction $p_dbAbstraction
    * @return bool
    */
   public function addPersistentRecord(DBAbstraction $p_dbAbstraction);

   /**
    * Updates the objects data in the database.
    * @param DBAbstraction $p_dbAbstraction
    * @return bool
    */
   public function updPersistentRecord(DBAbstraction $p_dbAbstraction);

   /**
    * Deletes the objects data in the database.
    * @param DBAbstraction $p_dbAbstraction
    * @return bool
    */
   public function delPersistentRecord(DBAbstraction $p_dbAbstraction);

   /**
    * @param DBAbstraction $p_dbAbstraction
    * @param bool $p_handleTransaction Default boolean TRUE.
    * @return bool
    */
   public function save(DBAbstraction $p_dbAbstraction, bool $p_handleTransaction =TRUE);
} // End interface