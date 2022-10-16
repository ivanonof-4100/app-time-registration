<?php
/**
 * Script-name  : bowling_game_renderer.class.php
 * Language     : PHP v7.x
 * Date created : 26/11-2020, Ivan
 * Last modified: 26/11-2020, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2020 by Ivan Mark Andersen
 *
 * Description
 *  Rendering of a bowling game.
 */
require_once(PATH_COMMON_RENDERS .'std_renderer.class.php');

class BowlingGameRenderer extends StdRenderer
{
  /**
   * @param resource $p_languageFileHandlerObj
   * @param boolean $p_isPrintPage
   *
   * @return BowlingGameRenderer
   */
  public function __construct($p_languageFileHandlerObj, $p_isPrintPage =false) {
     parent::__construct($p_languageFileHandlerObj, $p_isPrintPage);
  } // method constructor

  public function __destruct() {
     parent::__destruct();
  } // method destructor

  /**
   * @return BowlingGameRenderer
   */
  public static function getInstance($p_languageFileHandlerObj, $p_isPrintPage =false) : BowlingGameRenderer {
     return new BowlingGameRenderer($p_languageFileHandlerObj, $p_isPrintPage);      
  } // method getInstance

  /**
   * @return string
   */
  public static function getDomainTitle($p_pageTitle ='') {
     return sprintf(SITE_DOMAIN_TITLE, $p_pageTitle);
  } // method getDomainTitle

  /**
   * @return string
   */
  public static function getScriptName() : string {
     return basename($_SERVER['SCRIPT_NAME'], '.php');
  } // method getScriptName

  public function renderScoreboard() {
     $languageHandlerObj = $this->getInstance_languageFileHandler();
  //   $pageTitle = $languageHandlerObj->getLocalizedContent('STARTPAGE_TITLE', 'TEST', 'Bla');
     $pageTitle = $languageHandlerObj->getLocalizedContent('STARTPAGE_TITLE');

     // Get instance of template.
     $template = Template::getInstance('bowling_scoreboard_10pins.tpl');

     // Send the variables to the template.
     $template->assign('pageTitle', $pageTitle);
     $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
     $template->assign('scriptName', self::getScriptName());

     $template->display();
  } // method renderScoreboard
} // Ends class