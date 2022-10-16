<?php
namespace Common\Classes\Renderes;

use Common\Classes\Renderes\StdRenderer;

/**
 * Script-name  : page_renderer.class.php
 * Language     : PHP v7.x
 * Date created : 15/08-2016, Ivan
 * Last modified: 15/08-2016, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description
 *  Rendering of pages.
 */
class PageRenderer extends StdRenderer
{
  /**
   * @param resource $p_languageFileHandlerObj
   * @param boolean $p_isPrintPage
   */
  public function __construct($p_languageFileHandlerObj, $p_isPrintPage =false) {
     parent::__construct($p_languageFileHandlerObj, $p_isPrintPage);
  }

  public function __destruct() {
     parent::__destruct();
  }

  /**
   * @return PageRenderer
   */
  public static function getInstance($p_languageFileHandlerObj, $p_isPrintPage =false) : PageRenderer {
     return new PageRenderer($p_languageFileHandlerObj, $p_isPrintPage);      
  }

  /**
   * @return string
   */
  public static function getDomainTitle($p_pageTitle ='') {
     return sprintf(APP_DOMAIN_TITLE, $p_pageTitle);
  }

  /**
   * @return string
   */
  public static function getScriptName() : string {
     return basename($_SERVER['SCRIPT_NAME'], '.php');
  }

  public function renderStartpage() {
     $languageHandlerObj = $this->getInstance_languageFileHandler();
  //   $pageTitle = $languageHandlerObj->getLocalizedContent('STARTPAGE_TITLE', 'TEST', 'Bla');
     $pageTitle = $languageHandlerObj->getLocalizedContent('STARTPAGE_TITLE');

     // Get instance of template.
     $template = Template::getInstance('index.tpl');

     // Send the variables to the template.
     $template->assign('pageTitle', $pageTitle);
     $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
     $template->assign('pageMetaDescription', 'Northern-partners, tidsregistering, konsulenter');
     $template->assign('scriptName', self::getScriptName());
     $template->display();
  }

  public function renderPageNotFound() {
     $pageTitle = 'Side ikke fundet';
     $template = Template::getInstance('error_404.tpl');

     // Send the variables to the template.
     $template->assign('pageTitle', $pageTitle);
     $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
     $template->assign('pageMetaDescription', SITE_DOMAIN_NAME .', 404 fejl-side');
     $template->assign('scriptName', self::getScriptName());

     $template->display();
  }

  public function renderPageInternalError($p_arrLastError) {
     $pageTitle = 'Intern server-fejl';
     $template = Template::getInstance('error_500.tpl');

     // Send the variables to the template.
     $template->assign('pageTitle', $pageTitle);
     $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
     $template->assign('pageMetaDescription', SITE_DOMAIN_NAME .', 500 fejl-side');
     $template->assign('scriptName', self::getScriptName());
     $template->assign('errorTitle', 'An internal server-error occurred!');
     $template->assign('errorColor', 'red');

     if (is_array($p_arrLastError) && array_key_exists('message', $p_arrLastError)) {
       $template->assign('errorMessage', $p_arrLastError['message']);
     }
     if (is_array($p_arrLastError) && array_key_exists('file', $p_arrLastError)) {
       $template->assign('errorFile', $p_arrLastError['file']);
     }
     if (is_array($p_arrLastError) && array_key_exists('line', $p_arrLastError)) {
	    $template->assign('errorLine', $p_arrLastError['line']);
     }
  
     $template->display();
  }
} // Ends class