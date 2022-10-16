<?php
/**
 * Script-name  : admin_pages_renderer.class.php
 * Language     : PHP v7.x
 * Date created : IMA, 17/11-2016
 * Last modified: IMA, 17/11-2016
 * Developers   : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description
 *  Rendering of admin-pages.
 */
require_once(PATH_COMMON_RENDERS .'std_renderer.class.php');

class AdminPagesRenderer extends StdRenderer
{
  /**
   * @param resource $p_languageFileHandlerObj
   * @param boolean $p_isPrintPage
   *
   * @return AdminPagesRenderer
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
   * @return AdminPagesRenderer
   */
  public static function getInstance($p_languageFileHandlerObj, $p_isPrintPage =false)
  {
     return new AdminPagesRenderer($p_languageFileHandlerObj, $p_isPrintPage);      
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
     $pageTitle = $languageHandlerObj->getLocalizedContent('ADMIN_PAGES_TITLE');

     // Get instance of template.
     $template = Template::getInstance('admin_pages.tpl');

     // Send the variables to the template.
     $template->assign('pageTitle', $pageTitle);
     $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
     $template->assign('pageMetaDescription', 'Ivan Mark Andersen, '. SITE_DOMAIN_NAME .', Officiel hjemmeside, Full-stack Web-udvikler (LAMP)');
     $template->assign('scriptName', self::getScriptName());

     $template->display();
  } // method renderStartpage

} // Ends class
?>