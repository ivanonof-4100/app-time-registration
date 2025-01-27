<?php
namespace Common\Classes\Renderes;

use Common\Classes\Renderes\StdRenderer;

/**
 * Filename  : page_renderer.class.php
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
   * @param resource $p_languageFileHandler
   */
  public function __construct($p_languageFileHandler) {
     parent::__construct($p_languageFileHandler);
     $this->startOutputBuffering();
  }

  public function __destruct() {
     parent::__destruct();
  }

  /**
   * @return PageRenderer
   */
  public static function getInstance($p_languageFileHandler) : PageRenderer {
     return new PageRenderer($p_languageFileHandler);      
  }

  /**
   * @return string
   */
  public static function getDomainTitle($p_pageTitle ='') : string {
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
     $pageTitle = $languageHandlerObj->getLocalizedContent('STARTPAGE_TITLE');

     // Get instance of template.
     $template = Template::getInstance('index.tpl');

     // Send the variables to the template.
     $template->assign('pageTitle', $pageTitle);
     $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
     $template->assign('pageMetaDescription', 'Northern-partners, tidsregistering, konsulenter');
     $template->assign('scriptName', self::getScriptName());

     // Display
     $pageMetaDescription = 'Startpage';
     $pageMetaKeywords = 'Web-app, startpage';
     $this->displayAsPage($pageTitle, $pageMetaDescription, $pageMetaKeywords, $template->fetch());
  }

  public function renderPageNotFound() {
     $pageTitle = 'Side ikke fundet - Ivan';
     $template = Template::getInstance('error_404.tpl', Template::PATH_TEMPLATES_STD);

     // Send the variables to the template.
     $template->assign('pageTitle', $pageTitle);
     $template->assign('pageDomainTitle', self::getDomainTitle($pageTitle));
     $template->assign('pageMetaDescription', SITE_DOMAIN_NAME .', 404 fejl-side');
     $template->assign('scriptName', self::getScriptName());

     // Display
     $pageMetaDescription = 'Occured error page not found';
     $pageMetaKeywords = 'Web-app, page not found, 404';
     $this->displayAsPage($pageTitle, $pageMetaDescription, $pageMetaKeywords, $template->fetch());
  }

  public function renderPageInternalError($p_arrLastError) {
     $pageTitle = 'Intern server-fejl';
     $template = Template::getInstance('error_500.tpl', Template::PATH_TEMPLATES_STD);

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

     // Display
     $pageMetaDescription = 'Occured internal-error';
     $pageMetaKeywords = 'Web-app, internal-error, 500';
     $this->displayAsPage($pageTitle, $pageMetaDescription, $pageMetaKeywords, $template->fetch());
  }
} // End class