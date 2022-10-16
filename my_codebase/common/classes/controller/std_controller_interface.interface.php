<?php
namespace Common\Classes\Controller;

Interface StdControllerInterface {
   public static function getInstance(string $p_lang ='da', string $p_charset ='utf8');
}