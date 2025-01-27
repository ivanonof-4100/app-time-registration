<?php
namespace App\Modules\About\Classes\Renderes;

use Common\Classes\Renderes\Template;
use Common\Classes\Renderes\StdRenderer;
use Common\Classes\Renderes\MenuRenderer;
use Common\Classes\LanguagefileHandler;
use Common\Classes\Datetime\CustomDateTime;
use Common\Classes\Db\DBAbstraction;
use Exception;

class AboutRenderer extends StdRenderer {
    public function __construct(LanguagefileHandler $p_languagefileHandler) {
      parent::__construct($p_languagefileHandler);
    }

    public function __destruct() {
      parent::__destruct();
    }

    /**
     * @param LanguagefileHandler $p_languagefileHandler.
     */
    public static function getInstance(LanguagefileHandler $p_languagefileHandler) : AboutRenderer {
      return new AboutRenderer($p_languagefileHandler);
    }

    /**
     * @param DBAbstraction
     * @return void
     */
    public function renderAboutPage(DBAbstraction $p_dbAbstraction) : void {
        // Load language-file
        $languagefileHandler = $this->getInstance_languageFileHandler();
        $languagefileHandler->loadLanguageFile('custom_datetime');

        $menuRenderer = MenuRenderer::getInstance($languagefileHandler);
        $this->setMainNavigation($menuRenderer->render_mainMenu($p_dbAbstraction, $languagefileHandler->getLanguageIdent(), $this->getAttr_arrLangs()));

        $pageTitle = 'About me';
        $pageMetaDescription = 'About page description';
        $pageMetaKeywords = 'About, time-registration';
        $template = Template::getInstance('about_section.tpl', Template::PATH_TEMPLATES_SITE);
        // Send the variables to the template.
        $template->assign('resumeSessionId', session_id());

        try {
          $templateOutput = $template->fetch();
          $this->displayAsPage($pageTitle, $pageMetaDescription, $pageMetaKeywords, $templateOutput);
        } catch (Exception $e) {
          $this->displayAsPage($pageTitle, $pageMetaDescription, $pageMetaKeywords, $e->getMessage());
        }
    }
}