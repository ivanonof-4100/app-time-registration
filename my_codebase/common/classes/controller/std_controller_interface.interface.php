<?php
namespace Common\Classes\Controller;
use Common\Classes\StdApp;

Interface StdControllerInterface {
   public static function getInstance(string $p_lang ='da', string $p_charset ='utf8', StdApp $p_appInstance);
}