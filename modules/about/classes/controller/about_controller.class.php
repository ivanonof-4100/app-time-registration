<?php
namespace App\Modules\About\Classes\Controller;

use Exception;
use Common\Classes\StdApp;
use Common\Classes\Controller\StdController;
use Common\Classes\Controller\StdControllerInterface;
use Common\Classes\LanguagefileHandler;
/*
use Common\Classes\Helper\CustomToken;
use Common\Classes\ResponseCode;
use Common\Classes\RouteHandler;
use Common\Classes\InputHandler;
*/
use App\Modules\About\Classes\Renderes\AboutRenderer;

/**
 * Filename  : about_controller.class.php
 * Language     : PHP v7.4+
 * Date created : Ivan, 26/06-2024
 * Last modified: Ivan, 24/06-2024
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright: Copyright (C) 2024 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * This class handles the flow-control and all the coordination in the proces of handling everything about the about-page.
 */
class AboutController extends StdController implements StdControllerInterface {
    // Attributes
    /**
     * @var AboutRenderer
     */
    protected $rendererInstance;

    /**
     * Constructor
     * @param string $p_lang
     * @param string $p_charset
     * @param StdApp $p_appInstance
     */
    public function __construct(string $p_lang =APP_LANGUAGE_IDENT, string $p_charset ='utf8', StdApp $p_appInstance) {
      parent::__construct($p_appInstance);
      $this->rendererInstance = AboutRenderer::getInstance(LanguagefileHandler::getInstance(FALSE, $p_lang, $p_charset));
    }

    public function __destruct() {
      parent::__destruct();        
    }

    public function getInstance_renderer() : AboutRenderer {
      return $this->rendererInstance;
    }

    /**
     * @return AboutController
     */
    public static function getInstance(string $p_lang ='da', string $p_charset ='utf8', StdApp $p_appInstance) : AboutController {
      return new AboutController($p_lang, $p_charset, $p_appInstance);
    }

    /**
     * Initialize dependencies and the registry of the web-app.
     * @return void
     */
    public function initalizeDependencies() : void {
      // Start output-buffering.
      $rendererInstance = $this->getInstance_renderer();
      $rendererInstance->startOutputBuffering();

      $arrSettings = $this->getLoadedSettings();
      if (array_key_exists('app_lang_supported', $arrSettings)) {
        // Set supported languages
        $instanceRenderer = $this->getInstance_renderer();
        $instanceRenderer->setAttr_arrLangs($arrSettings['app_lang_supported']);
      }

      try {
        // Connect to database and start session-handling.
        $this->initDependencies($arrSettings);
      } catch (Exception $e) {
        // Handled errors.
        $rendererInstance = $this->getInstance_renderer();
        $rendererInstance->renderHandledAlert($e->getMessage());
        exit(0);
      }
    }

    public function handleAbout() : void {
      $this->initalizeDependencies();

      // Get the active database-connection from the codebase-registry.
      $codebaseRegistry = $this->getInstance_codebaseRegistry();
      $dbAbstractionInstance = $codebaseRegistry->getInstance_dbConnection();

      $rendererInstance = $this->getInstance_renderer();
      $rendererInstance->renderAboutPage($dbAbstractionInstance);
    }
} // End class