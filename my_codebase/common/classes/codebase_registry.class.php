<?php
namespace Common\Classes;

use Common\Classes\StdApp;
use Common\Classes\Helper\CustomToken;

/**
 * Filename     : codebase_registry.class.php
 * Language     : PHP v7.x
 * Date created : 25/04-2012, Ivan
 * Last modified: 20/05-2023, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * Description:
 * This class handles access to several instances of classes used and needed in my codebase.
 * 
 * By using the registry design-pattern we are able to eliminate use of global variables in the framework.
 * Now we have a handle to the variable to access it and write it in the registry.
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

  /**
   * @param string $p_entryName
   * @param $p_entryContent
   */
  protected function setRegistryEntry(string $p_entryName, $p_entryContent) : void {
      $this->arrRegistry[$p_entryName] = $p_entryContent;
  }

  protected function getRegistryEntry($p_entryName) {
     if ($this->doesEntryExists($p_entryName)) {
       // Entry found - lets return the content.
       return $this->arrRegistry[$p_entryName];
     } else {
       // No entry was found with that entry-name.
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
  public function getInstance_appInstance() : StdApp {
     return $this->getRegistryEntry('app_instance');
  }

  public function setInstance_sessionHandler($p_sessionHandler) : void {
     $this->setRegistryEntry('session_handler', $p_sessionHandler);
  }

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
   * @return bool
   */
  public function doesEntryExists_dbConnection() : bool {
     return $this->doesEntryExists('db_primary');
  }

  /**
   * @return bool
   */
  public function doesEntryExists_securityToken() : bool {
     return $this->doesEntryExists('security_token');
  }

  /**
   * @param CustomToken $p_securityToken
   */
  public function setInstance_securityToken(CustomToken $p_securityToken) : void {
     $this->setRegistryEntry('security_token', $p_securityToken);
  }

  /**
   * @return CustomToken
   */
  public function getInstance_securityToken() : CustomToken {
     return $this->getRegistryEntry('security_token');
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