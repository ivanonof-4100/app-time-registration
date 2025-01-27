<?php
namespace Common\Classes\Renderes;

use Common\Classes\Renderes\StdRenderer;

/**
 * Filename     : page_renderer.class.php
 * Language     : PHP v7.x
 * Date created : IMA, 15/08-2016
 * Last modified: IMA, 15/08-2016
 * Developers   : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description:
 * Rendering of contact-pages.
 */

class ContactPageRenderer extends StdRenderer
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
  }

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

  public function renderPageInternalError($p_arrLastError)
  {
     $pageTitle = 'Intern server-fejl';
     $template = Template::getInstance('error_500.tpl');

     // Send the variables to the template.
     $template->assign('pageTitle', $pageTitle);
     $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
     $template->assign('pageMetaDescription', SITE_DOMAIN_NAME .', 500 fejl-side');
     $template->assign('scriptName', self::getScriptName());
//     $template->assign('errorTitle', $errorTitle);
     $template->assign('errorColor', 'red');
     $template->assign('errorMessage', $p_arrLastError['message']);
	 $template->assign('errorFile', $p_arrLastError['file']);
	 $template->assign('errorLine', $p_arrLastError['line']);

     $template->display();
  } // method renderPageInternalError

} // Ends class
?>