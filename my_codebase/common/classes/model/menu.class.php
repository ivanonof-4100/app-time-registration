<?php
namespace Common\Classes\Model;

use PDO;
use PDOException;
use Common\Classes\Db\DBAbstraction;
use Common\Classes\Model\StdModel;
use Common\Classes\Model\SaveableObjectInterface;
use Exception;

/** 
 * Filename     : menu.class.php
 * Language     : PHP v7.x
 * Date created : 29/03-2023, Ivan
 * Last modified: 28/04-2023, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright: Copyright (C) 2023 by Ivan Mark Andersen
 * 
 * Description:
 * My custom menu class to handle displaying dynamic menus.
 */
 class Menu extends StdModel implements SaveableObjectInterface
 {
    const DB_TABLE_NAME = 'menus';
    const DB_TABLE_ALIAS = 'm';
    const DB_COLUMNS = 'menu_id,menu_parent_id,menu_tag';

    /**
     * @var int
     */
    protected $menu_id;

    /**
     * @var int
     */
    protected $menu_parent_id;

    /**
     * @var string
     */
    protected $menu_tag; // eg. 'main'
  
    /**
     * @var array
     */
    protected $rel_menu_items;

    public function __construct(int $p_menuId, ? int $p_menuParentId, string $p_menuTag ='') {
      parent::__construct();

      $this->setAttr_menu_id($p_menuId);
      $this->setAttr_menu_parent_id($p_menuParentId);
      $this->setAttr_menu_tag($p_menuTag);
    }

    public function __destruct() {
      parent::__destruct();
    }

    /**
     * @return Menu
     */
    public static function getInstance(int $p_menuId, ? int $p_menuParentId, string $p_menuTag ='') : Menu {
      return new Menu($p_menuId, $p_menuParentId, $p_menuTag);
    }

    protected function setAttr_menu_id(int $p_menuId) : void {
       $this->menu_id = $p_menuId;
    }

    /**
     * @return int
     */
    public function getAttr_menu_id() : int {
       return $this->menu_id;
    }

    protected function setAttr_menu_parent_id(?int $p_menuParentId) : void {
      $this->menu_parent_id = $p_menuParentId;
    }

    /**
     * @return int
     */
    public function getAttr_menu_parent_id() : ?int {
      return $this->menu_parent_id;
    }

    protected function setAttr_menu_tag(string $p_menuTag) : void {
      $this->menu_tag = (string) $p_menuTag;
    }

    /**
     * @return string
     */
    public function getAttr_menu_tag() : string {
      return $this->menu_tag;
    }

    protected function setRelated_menuItems(array $p_menuItems) : void {
       $this->rel_menu_items = $p_menuItems;
    }

    public function getRelated_menuItems() : array {
      return $this->rel_menu_items;
    }

    /**
     * Checks if a given record exists with the given unique primary-UUID.
     * @param DBAbstraction $p_dbAbstraction
     * @param string $p_menuTag
     * @return bool
    */
    public static function doesExists(DBAbstraction $p_dbAbstraction, string $p_menuTag) : bool {
        $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
        if (DBAbstraction::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
          // Setup the SQL-statement.
          $sql = sprintf('SELECT count(%s.menu_id) AS NUM_RECORDS_FOUND', self::DB_TABLE_ALIAS);
          $sql .= PHP_EOL;
          $sql .= sprintf('FROM %s %s', self::DB_TABLE_NAME, self::DB_TABLE_ALIAS);
          $sql .= PHP_EOL;
          $sql .= sprintf('WHERE %s.menu_tag = :menu_tag', self::DB_TABLE_ALIAS);
          $sql .= PHP_EOL;
          $sql .= 'LIMIT 1';
  
          // Prepare and execute the SQL-statement.
          $pdoStatement = $dbPDOConnection->prepare($sql);
          if (!$pdoStatement) {
            trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
          } else {
            try {
              // Map parameters
              $pdoStatement->bindParam(':menu_tag', $p_menuTag, PDO::PARAM_STR);
              // Execute and return the boolean-result.
              return $p_dbAbstraction->fetchBooleanResult($pdoStatement);
            } catch (PDOException $e) {
              // Re-throw
              throw new Exception($e->getMessage(), $e->getCode());
            }
          }
        } else {
          trigger_error('Unable to check if a record-id allready exists because of unavaiable active database-connection ...', E_USER_ERROR);
        }
    }

    /**
     * @param DBAbstraction $p_dbAbstraction,
     * @param string $p_tagName Unique string-tag that indentifies the current menu.
     * @return Menu
     */
    public static function getInstance_byTagName(DBAbstraction $p_dbAbstraction, string $p_tagName ='main') : Menu {
        // Retrive PDO database-connection.
        $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
        if (DBAbstraction::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
          if (self::doesExists($p_dbAbstraction, $p_tagName)) {
            $sql = 'SELECT '. self::DB_COLUMNS;
            $sql .= PHP_EOL;
            $sql .= sprintf('FROM %s', self::DB_TABLE_NAME);
            $sql .= PHP_EOL;
            $sql .= 'WHERE menu_tag = :menu_tag';
  
            // Prepare and execute the SQL-statement.
            $pdoStatement = $dbPDOConnection->prepare($sql);
            if (!$pdoStatement) {
              trigger_error(__METHOD__ .': Unable to prepare the SQL-statement. The message was the following: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
            } else {
              try {
                // Map parameters and execute.
                $pdoStatement->bindParam(':menu_tag', $p_tagName, PDO::PARAM_STR);
                $pdoStatement->execute();
  
                $arrRowAssoc = $p_dbAbstraction->fetchRow_asAssocArray($pdoStatement);
                $menuInstance = self::getInstance($arrRowAssoc['menu_id'],
                                                  $arrRowAssoc['menu_parent_id'],
                                                  $arrRowAssoc['menu_tag']);
                $arrMenuItems = $menuInstance->fetchMenuItems_byMenuTag($p_dbAbstraction, $menuInstance->getAttr_menu_tag());
                $menuInstance->setRelated_menuItems($arrMenuItems);
                return $menuInstance;
              } catch (PDOException $e) {
                // Re-throw
                throw new Exception($e->getMessage(), $e->getCode());
              }
            }
          } else {
            trigger_error('Requested menu with tag-name: '. $p_tagName.' does not exists ...', E_USER_NOTICE);
          }
        } else {
          trigger_error('Unable to retrive any data because of an unavaiable database-connection ...', E_USER_ERROR);
        }
    }

    /**
     * Retrives both menu-items and sub-menus and the menu-items on the sub-menus to the menu that has the given tag.
     * @param DBAbstraction $p_dbAbstraction
     * @param string $p_menuTag = Default 'main'
     * @param string $p_languageIdent
     * @return array
     */
    public static function fetchMenuItems_byMenuTag(DBAbstraction $p_dbAbstraction,
                                             string $p_menuTag = 'main',
                                             string $p_languageIdent =APP_LANGUAGE_IDENT) : array {
      // Retrive PDO database-connection.
      $dbPDOConnection = $p_dbAbstraction->getPDOConnectionInstance();
      if (DBAbstraction::doesDatabaseConnection_meetCriterias($dbPDOConnection)) {
        // Setup the SQL-statment.
        $sql = '(SELECT mi.menu_id';
        $sql .= ',m.menu_parent_id';
        $sql .= ',mi.menu_item_id';
        $sql .= ',mi.menu_item_seqno';
        $sql .= ',mi.sub_menu_id';
        $sql .= ",'menu_item' AS menu_item_type";
        $sql .= ',mil.language_ident';
        $sql .= ',mil.menu_item_title';
        $sql .= ',mil.menu_item_uri';  
        $sql .= PHP_EOL;
        $sql .= 'FROM menus m';
        $sql .= ",menu_items mi LEFT JOIN menu_items_localized mil ON (mil.menu_item_id = mi.menu_item_id AND mil.language_ident = :language_ident)";
        $sql .= PHP_EOL;
        $sql .= 'WHERE mi.menu_id = m.menu_id';
        $sql .= PHP_EOL;
        // Sub-select that makes it possible to fetch all related sub-menus and menu-items.
        $sql .= 'AND mi.menu_id IN ((SELECT m4.menu_id';
        $sql .= PHP_EOL;
        $sql .= 'FROM menus m4';
        $sql .= PHP_EOL;
        $sql .= 'WHERE m4.menu_tag = :menu_tag)';
        $sql .= PHP_EOL;
        $sql .= 'UNION ALL';
        $sql .= PHP_EOL;
        $sql .= '(SELECT m5.menu_id';
        $sql .= PHP_EOL;
        $sql .= 'FROM menus m5';
        $sql .= PHP_EOL;
        $sql .= 'WHERE m5.menu_parent_id IN (';
        $sql .= PHP_EOL;
        $sql .= 'SELECT m6.menu_id';
        $sql .= PHP_EOL;
        $sql .= 'FROM menus m6';
        $sql .= PHP_EOL;
        $sql .= 'WHERE m6.menu_tag = :menu_tag) ))';
        $sql .= PHP_EOL;
        $sql .= 'AND ISNULL(mi.sub_menu_id))';
        $sql .= PHP_EOL;
        $sql .= 'UNION ALL';
        $sql .= PHP_EOL;
        $sql .= '(SELECT mi.menu_id';
        $sql .= ',m.menu_parent_id';
        $sql .= ',mi.menu_item_id';
        $sql .= ',mi.menu_item_seqno';
        $sql .= ',mi.sub_menu_id';
        $sql .= ",'sub_menu' AS menu_item_type";
        $sql .= ',mil.language_ident';
        $sql .= ',mil.menu_item_title';
        $sql .= ',mil.menu_item_uri';
        $sql .= PHP_EOL;
        $sql .= 'FROM menus m';
        $sql .= PHP_EOL;
        $sql .= ",menu_items mi LEFT JOIN menu_items_localized mil ON (mil.menu_item_id = mi.menu_item_id AND mil.language_ident = :language_ident)";
        $sql .= PHP_EOL;
        $sql .= 'WHERE  mi.menu_id = m.menu_id';
        $sql .= PHP_EOL;
        $sql .= 'AND NOT ISNULL(mi.sub_menu_id))';
        $sql .= PHP_EOL;
        $sql .= 'ORDER BY menu_item_seqno, menu_id ASC';

        // Prepare and execute the SQL-statement.
        $pdoStatement = $dbPDOConnection->prepare($sql);
        if (!$pdoStatement) {
          trigger_error(__METHOD__ .': Unable to prepare the SQL-statement: '. $dbPDOConnection->errorInfo(), E_USER_ERROR);
        } else {
          try {
            // Map parameters and execute.
            $pdoStatement->bindParam(':menu_tag', $p_menuTag, PDO::PARAM_STR);
            $pdoStatement->bindParam(':language_ident', $p_languageIdent, PDO::PARAM_STR);
            $pdoStatement->execute();
            return $p_dbAbstraction->fetchAll_asAssocArray($pdoStatement);
          } catch (PDOException $e) {
            trigger_error('MENU error: '. $e->getMessage(), E_USER_ERROR);
          }
        }
      }
    }

    /**
     * Fetches dynamic meta-data for the requested URI and language.
     */
/*
    public function fetchMetaData(string $p_requestURI, $p_languageIdent) {
    }
*/
 } // End class