<?php
/**
 * Script-name  : debug_message_handler.class.php 
 * Language     : PHP v5.x
 * Date created : IMA, 19/11-2012
 * Last modified: IMA, 20/11-2012
 * Author(s)    : @author (IMA) Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2012 by Ivan Mark Andersen
 *
 * Description
 *  This class wraps the methods on a password as a non-persistent object.
 *  The log-level of the debugging defines which messages you are interested in and their lower levels.
 *  By default the debug-level is at the highest level so you will get every debugging messages when debugging is on.
 * 
 *  @example:
 *
 *  // Log debug-messages from the level of content-classes and down. 
 *  $debugMesgHandlerObj = new DebugMessageHandler(DEBUG_LEVEL_CONTENT);
 *  $debugMesgHandlerObj->addMessage(DEBUG_LEVEL_CONTENT, __METHOD__ .': Password was verifyed!', __FILE__, __LINE__);
 *
 *  $formatedDebugOutput = $debugMesgHandlerObj->getReportedMessages();
*/
require_once(PATH_COMMON_CLASSES .'debug_message.class.php');

class DebugMessageHandler
{
  const DEBUG_THRESHOLD_NONE =0;
  const DEBUG_THRESHOLD_CONFIG =1;
  const DEBUG_THRESHOLD_STARTERSCRIPT =2;
  const DEBUG_THRESHOLD_DB =3;
  const DEBUG_THRESHOLD_CONTENT =4;
  const DEBUG_THRESHOLD_CONTROLLER =5;
  const DEBUG_THRESHOLD_RENDERER =6;

  // Attributes
  protected $debug_threshold;
  protected $arr_debug_messages;

  // Methods

  /**
   * Default constructor initalizes the instance in case of a new statement.
   * @param int|boolean $p_customThreshold Default FALSE
   */
  public function __construct($p_customThreshold =FALSE) {
     if (!DEBUG) {
       $this->setAttr_debug_threshold(self::DEBUG_THRESHOLD_NONE);
       $this->arr_debug_messages = null;
     } else {
       if ($p_customThreshold) {
         $this->setAttr_debug_threshold($p_customThreshold);
       } else {
         // Use default debug-threshold.
         $this->setAttr_debug_threshold(self::DEBUG_THRESHOLD_CONTROLLER);
       }

       $this->arr_debug_messages = array();
     }
  } // method __construct

  public function __destruct() {
  } // method __destruct

  protected function setAttr_debug_threshold($p_debugThreshold = self::DEBUG_THRESHOLD_RENDERER) {
     $this->debug_threshold = (int) $p_debugThreshold;
  } // method setAttr_debug_threshold

  /** 
   * @return int
   */
  protected function getAttr_debug_threshold() {
     return $this->debug_threshold;
  } // method getAttr_debug_threshold

  /**
   * Adds a debug-message.
   * 
   * @param int $p_debugLevel
   * @param string $p_debugText
   * @param string $p_filename Optional executing filename (use __FILE__)
   * @param int $p_lineNumber Optional line number (use __LINE__)
   */
  public function addMessage($p_debugLevel, $p_debugText, $p_filename ='', $p_lineNumber =FALSE) {
     $currentDebugThreshold = $this->getAttr_debug_threshold();
     if (($currentDebugThreshold > self::DEBUG_THRESHOLD_NONE) && ($p_debugLevel <= $currentDebugThreshold)) {
       // Add debug-message.
       $this->arr_debug_messages[] = new DebugMessage($p_debugText, $p_filename, $p_lineNumber);
     }
  } // method addMessage

  /**
   * Retrives the formated output of every debug-message added according to the setup debug-level threshold.
   * @return string
   */
  public function getReportedMessages() {
     require_once(PATH_CLASSES_RENDERS .'template.class.php');

     // Options of debug-level to use.
     $arrDebugLevels = array();

     // These are my fixed debug-levels defined in the configuration-file of the codebase.
     $arrDebugLevels[DEBUG_LEVEL_CONFIG] = 'Configuration';
     $arrDebugLevels[DEBUG_LEVEL_STARTERSCRIPT] = 'Starter-scripts'; 
     $arrDebugLevels[DEBUG_LEVEL_DB] = 'Database-operations';
     $arrDebugLevels[DEBUG_LEVEL_CONTENT] = 'Content-classes';
     $arrDebugLevels[DEBUG_LEVEL_CONTROLLER] = 'Controller-classes';
     $arrDebugLevels[DEBUG_LEVEL_RENDERER] = 'Renderer-classes';

     // Setup the template to use.
     $templateObj = new Template('std_list.debug_messages.tpl', PATH_TEMPLATES_STANDARD);

     // Send the variables to the template.
     $templateObj->assign('arrDebugMesg', $this->arr_debug_messages);
     $templateObj->assign('arrDebugLevels', $arrDebugLevels);
     $templateObj->assign('curDebugLevel', $this->getAttr_debug_threshold());

     // Return the formated output.
     return $templateObj->fetch();
  } // method getReportedMessages

  public static function getInstance_fromSession() {
     if (isset($_SESSION['session_handler'])) {
       $sessionHandlerObj = $_SESSION['session_handler'];
       $siteHandlerObj = $sessionHandlerObj->get('site_handler');

       if (isset($siteHandlerObj)) {
         $codebaseRegistryObj = $siteHandlerObj->getInstance_codebaseRegistry();
         return $codebaseRegistryObj->getInstance_debugHandler();
       } else {
         return null;
       }
     } else {
       return null;
     } 
  } // method getInstance_fromSession
} // End class