<?php
namespace Common\Classes\Model;

use Common\Classes\Model\StdModel;
use Common\Classes\Model\SaveableObjectInterface;

/** 
 * Filename     : menu_item.class.php
 * Language     : PHP v7.x
 * Date created : 29/03-2023, Ivan
 * Last modified: 30/03-2023, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright: Copyright (C) 2023 by Ivan Mark Andersen
 * 
 * Description:
 *  My custom menu-item class that plays a role in handling displaying dynamic menus.
 */
class MenuItem extends StdModel implements SaveableObjectInterface
{
   const DB_TABLE_NAME = 'menu_items';
   const DB_TABLE_ALIAS = 'mi';
   const DB_COLUMNS = 'menu_item_id, menu_item_seqno, menu_item_uri, menu_id';

   /**
    * @var int
    */
   protected $menu_item_id;

   /**
    * @var int
    */
   protected $menu_item_seqno;

   /**
    * @var string
    */
   protected $menu_item_uri;

   /**
    * @var string
    */
   protected $menu_item_title;

   public function __construct(int $p_itemId =0,
                               int $p_itemSeqNo =0,
                               string $p_itemUri ='',
                               string $p_itemTitle ='') {
      parent::__construct();
      $this->setAttr_menu_item_id($p_itemId);
      $this->setAttr_menu_item_seqno($p_itemSeqNo);
      $this->setAttr_menu_item_uri($p_itemUri);
      $this->setAttr_menu_item_title($p_itemTitle);
   }

   public function __destruct() {
      parent::__destruct();
   }

   public static function getInstance(int $p_itemId =0,
                               int $p_itemSeqNo =0,
                               string $p_itemUri ='',
                               string $p_itemTitle ='') : MenuItem {
      return new MenuItem($p_itemId, $p_itemSeqNo, $p_itemUri, $p_itemTitle);
   }

   // Settter and getter methods
   protected function setAttr_menu_item_id(int $p_itemId =0) : void {
      $this->menu_item_id = $p_itemId;
   }

   /**
    * @return int
    */
   public function getAttr_menu_item_id() : int {
      return $this->menu_item_id;
   }

   protected function setAttr_menu_item_seqno(int $p_seqNo =0) : void {
      $this->menu_item_seqno = $p_seqNo;
   }

   /**
    * @return int
    */
   public function getAttr_menu_item_seqno() : int {
      return $this->menu_item_seqno;
   }

   protected function setAttr_menu_item_uri(string $p_uri ='/') : void {
      $this->menu_item_uri = $p_uri;
   }

   /**
    * @return string
    */
   public function getAttr_menu_item_uri() : string {
      return $this->menu_item_uri;
   }

   protected function setAttr_menu_item_title(string $p_title ='') : void {
      $this->menu_item_title = (string) $p_title; 
   }

   /**
    * @return string
    */
   public function getAttr_menu_item_title() : string {
      return $this->menu_item_title;
   }
}