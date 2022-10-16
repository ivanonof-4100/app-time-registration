<?php
namespace Common\Classes;

use Common\Classes\StdApp;

/**
 * Filename     : codebase_registry.class.php
 * Language     : PHP v5.x
 * Date created : 25/04-2012, Ivan
 * Last modified: 25/04-2012, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * Description:
 *  This class handles access to several instances of classes used and needed in my codebase.
 * 
 *  By using the registry-design-pattern we are able to eliminate use of global variables in the framework.
 *  And yet in a smart way - now we have a handle to the variable, that lets you use and access it.
 * 
 *  This way globally used variable are set once and accessed zero or many times using dedicated methods.
 *
 *  For safety reasons and for a simpler program-design, it is not possible to overwrite existing registry-entries.
 *  This way we do not have to worry so much about - What if that happend?!
*/
class CodebaseRegistry
{
  protected $arrRegistry;

  /**
   * @return CodebaseRegistry
  */
  public function __construct() {
     $this->arrRegistry = array();
  }

  public function __destruct() {
  }

  public function __clone() {
  	 $this->arrRegistry = clone $this->arrRegistry;
  }

  /**
   * @return CodebaseRegistry
   */
  public static function getInstance() : CodebaseRegistry {
  	 return new CodebaseRegistry();
  }

  /**
   * @param string $p_entryName
   * @return bool
  */
  protected function doesEntryExists($p_entryName) : bool {
     return array_key_exists($p_entryName, $this->arrRegistry);
  }

  protected function setRegistryEntry($p_entryName, $p_entryContent) {
     if (!$this->doesEntryExists($p_entryName)) {
       // Not all-ready set.
       $this->arrRegistry[$p_entryName] = $p_entryContent;
     }
// Or Maby the entry should just be set no matter when. The program depends on it.
//     $this->arrRegistry[$p_entryName] = $p_entryContent;
/*
     else {
       // We dont allow the entry being set twice.
       trigger_error(__METHOD__ .': Registry-entry <b>'.$p_entryName .'</b> was allready in use, so we did NOT overwrite the instance in the registry ...', E_USER_WARNING);
     }
*/
  }

  protected function getRegistryEntry($p_entryName) {
     if ($this->doesEntryExists($p_entryName)) {
       // Entry found - lets return the content.
       return $this->arrRegistry[$p_entryName];
     } else {
       // No entry was found with that entry-name.
       trigger_error(__METHOD__ .': Requested registry-entry <b>'.$p_entryName .'</b> was NOT used in the registry ...', E_USER_WARNING);
       return null;
     }
  }

  /**
   * @param StdApp $p_app
   */
  public function setInstance_appInstance(StdApp $p_app) : void {
     $this->setRegistryEntry('app_instance', $p_app);
  }

  /**
   * @return StdApp
   */
  public function getInstance_appInstance() {
     $this->getRegistryEntry('app_instance');
  }

  public function setInstance_errorHandler($p_errorHandlerObj) : void {
     $this->setRegistryEntry('error_handler', $p_errorHandlerObj);
  }

  public function getInstance_errorHandler() {
     return $this->getRegistryEntry('error_handler');
  }


  public function setInstance_sessionHandler($p_sessionHandlerObj) : void {
     $this->setRegistryEntry('session_handler', $p_sessionHandlerObj);
  }

  /**
   * @return SessionHandler
   */
  public function getInstance_sessionHandler() {
     return $this->getRegistryEntry('session_handler');
  }

  public function setInstance_outputBuffer($p_outputBufferObj) : void {
  	 $this->setRegistryEntry('output_buffer', $p_outputBufferObj);
  }

  public function getInstance_outputBuffer() {
  	 return $this->getRegistryEntry('output_buffer');
  }

  public function setInstance_languageFileHandler($p_languageFileHandlerObj) {
     $this->setRegistryEntry('language_handler', $p_languageFileHandlerObj);
  }

  /**
   * @return mixed
  */
  public function getInstance_languageFileHandler() {
     return $this->getRegistryEntry('language_handler');
  }

  public function setInstance_dbConnection($p_dbObj) : void {
     $this->setRegistryEntry('db_primary', $p_dbObj);
  }

  /**
   * @return mixed
  */
  public function getInstance_dbConnection() {
     return $this->getRegistryEntry('db_primary');
  }

  /**
   * @return boolean
   */
  public function doesEntryExists_dbConnection() {
     return $this->doesEntryExists('db_primary');
  }

  public function setInstance_debugHandler($p_debugObj) : void {
     $this->setRegistryEntry('debug_handler', $p_debugObj);
  }

  /**
   * @return mixed
   */
  public function getInstance_debugHandler() {
     return $this->getRegistryEntry('debug_handler');
  }
} // End class