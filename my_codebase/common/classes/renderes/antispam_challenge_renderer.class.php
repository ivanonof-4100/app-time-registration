<?php
/**
 * Filename  : antispam_challenge_renderer.class.php
 * Language     : PHP v7.x
 * Date created : IMA, 06/11-2016
 * Last modified: IMA, 06/11-2016
 * Developers   : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description
 *  Rendering of anti-spam challenge images.
 */
require_once(PATH_COMMON_RENDERS .'std_renderer.class.php');

class AntiSpamChallengeRenderer extends StdRenderer
{
  /**
   * @param resource $p_languageFileHandlerObj
   * @param boolean $p_isPrintPage
   *
   * @return PageRenderer
   */
  public function __construct($p_languageFileHandlerObj, $p_isPrintPage =false)
  {
     parent::__construct($p_languageFileHandlerObj, $p_isPrintPage);
  } // method constructor

  public function __destruct()
  {
     parent::__destruct();
  } // method destructor

  /**
   * @return PageRenderer
   */
  public static function getInstance($p_languageFileHandlerObj, $p_isPrintPage =false)
  {
     return new PageRenderer($p_languageFileHandlerObj, $p_isPrintPage);      
  } // method getInstance

  /**
   * @return string
   */
  public static function getDomainTitle($p_pageTitle ='')
  {
     return sprintf(SITE_DOMAIN_TITLE, $p_pageTitle);
  } // method getDomainTitle

  public static function getScriptName()
  {
     return basename($_SERVER['SCRIPT_NAME'], '.php');
  } // method getScriptName

  public function renderStartpage()
  {
     $languageHandlerObj = $this->getInstance_languageFileHandler();
  //   $pageTitle = $languageHandlerObj->getLocalizedContent('STARTPAGE_TITLE', 'TEST', 'Bla');
     $pageTitle = $languageHandlerObj->getLocalizedContent('STARTPAGE_TITLE');

     // Get instance of template.
     $template = Template::getInstance('index.tpl');

     // Send the variables to the template.
     $template->assign('pageTitle', $pageTitle);
     $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
     $template->assign('pageMetaDescription', 'Ivan Mark Andersen, '. SITE_DOMAIN_NAME .', Officiel hjemmeside, Full-stack Web-udvikler (LAMP)');
     $template->assign('scriptName', self::getScriptName());

     $template->display();
  } // method renderStartpage

  public function renderPageNotFound()
  {
     $pageTitle = 'Side ikke fundet';
     $template = Template::getInstance('error_404.tpl');

     // Send the variables to the template.
     $template->assign('pageTitle', $pageTitle);
     $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
     $template->assign('pageMetaDescription', SITE_DOMAIN_NAME .', 404 fejl-side');
     $template->assign('scriptName', self::getScriptName());

     $template->display();
  } // method renderPageNotFound

} // Ends class