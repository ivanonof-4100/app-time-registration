<?php
namespace Common\Classes\Renderes;

use Common\Classes\LanguagefileHandler;
use Common\Classes\Renderes\StdRenderer;
use Common\Classes\Renderes\Template;
use Common\Classes\Db\DBAbstraction;
use Common\Classes\Model\Menu;
use Exception;

class MenuRenderer extends StdRenderer
{
  public function __construct(LanguagefileHandler $p_languagefileHandler) {
    parent::__construct($p_languagefileHandler);
  }

  public function __destruct() {
    parent::__destruct();
  }

  /**
   * @return MenuRenderer
   */
  public static function getInstance(LanguagefileHandler $p_languagefileHandler) : MenuRenderer {
    return new MenuRenderer($p_languagefileHandler);
  }

  /**
   * @param DBAbstraction $p_dbAbstraction
   * @param string $p_langIdent
   * @param array $p_arrSupportedLangs
   * @throws Exception
   * @return string
   */
   public function render_mainMenu(DBAbstraction $p_dbAbstraction,
                                   string $p_langIdent = APP_LANGUAGE_IDENT,
                                   array $p_arrSupportedLangs) : string {
      try {
        $arrMenuItems = Menu::fetchMenuItems_byMenuTag($p_dbAbstraction, 'main', $p_langIdent);

        // Get instance of Template
        $template = Template::getInstance('menu.tpl', Template::PATH_TEMPLATES_STD);
        $template->assign('arrMenuItems', $arrMenuItems);
        $template->assign('arrLangs', $p_arrSupportedLangs);
        $template->assign('curURIPrefix', substr($_SERVER['REQUEST_URI'], 0, 4));
        $template->assign('arrConfigPaths', Template::getTemplatePaths());
        return $template->fetch();
      } catch (Exception $e) {
         // Re-throw the exception.
         throw new Exception($e->getMessage(), $e->getCode());
      }
  }
} // End class