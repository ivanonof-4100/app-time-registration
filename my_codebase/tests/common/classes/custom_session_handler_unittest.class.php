<?php
use PHPUnit\Framework\TestCase;

/*
 Read more the manual:
 https://phpunit.de/manual/current/en/extending-phpunit.html
*/
require_once '/data/demo_codebase/config/ivanonof_codebase.conf.php';
require_once PATH_COMMON_CLASSES .'custom_session_handler.class.php';

class CustomSessionHandlerUnitTest extends PHPUnit_Framework_Testcase
{
   private $testObj;

   /**
    * @return MyStringUnitTest
    */
   public function __construct()
   {
      parent::__construct();
   } // method __construct

   public function setup()
   {
      $this->testObj = CustomSessionHandler::getInstance();
   } // method setup

   public function getTestInstance()
   {
      return $this->testObj;
   } // method getTestInstance

   public function testInstanceType()
   {
      $sessionHandlerObj = $this->getTestInstance();
      $this->assertInstanceOf('CustomSessionHandler', $sessionHandlerObj, "The test-instance was not an instance of the expected class ...");
   } // method testInstanceType
 /*  
   public function testEncodeExistingContent()
   {
      $strObj = $this->getTestInstance();
      $this->assertContains('Carmen og Ivan', $strObj->getAttr_str(), 'The content of the string is NOT as we expected ...', TRUE);
   } // method testGetExistingContent
*/
} // End class