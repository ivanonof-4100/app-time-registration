<?php
namespace Common\Classes\Renderes;

use Common\Classes\LanguagefileHandler;
use Common\Classes\Renderes\StdRenderer;

/**
 * Filename  : startpage_renderer.class.php
 * Language     : PHP v7.x
 * Date created : 03/07-2023, Ivan
 * Last modified: 03/07-2023, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2023 by Ivan Mark Andersen
 *
 * Description
 * Rendering the front-page of the homepage.
 */
class StartpageRenderer extends StdRenderer
{
   /**
    * @param resource $p_languageFileHandler
    */
   public function __construct($p_languageFileHandler) {
     parent::__construct($p_languageFileHandler);
   }

   public function __destruct() {
     parent::__destruct();
   }

   /**
    * @param resource $p_languageFileHandler
    * @return StartpageRenderer
    */
   public static function getInstance($p_languageFileHandler) : StartpageRenderer {
     return new StartpageRenderer($p_languageFileHandler);
   }

   public function renderStartpage() : void {
     echo 'Startsiden!';
   }
} // End class