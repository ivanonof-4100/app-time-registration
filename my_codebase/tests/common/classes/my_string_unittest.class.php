<?php
use PHPUnit\Framework\TestCase;

/*
 Read more the manual:
 https://phpunit.de/manual/current/en/extending-phpunit.html
*/
require_once '/data/demo_codebase/config/ivanonof_codebase.conf.php';
require_once PATH_COMMON_CLASSES .'my_string.class.php';

class MyStringUnitTest extends PHPUnit_Framework_Testcase
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
      $this->testObj = MyString::getInstance('UTF-8', 'Katja og René, Carmen og Ivan, Kristina og Rudi, Mor og Far, Mette og Per, Maria og Kim');
   } // method setup

   public function getTestInstance()
   {
      return $this->testObj;
   } // method getTestInstance

   public function testInstanceType()
   {
      $strObj = $this->getTestInstance();
      $this->assertInstanceOf('MyString', $strObj, "The test-instance was not an instance o f expected class ...");
   } // method testInstanceType
   
   public function testGetExistingContent()
   {
      $strObj = $this->getTestInstance();
      $this->assertContains('Carmen og Ivan', $strObj->getAttr_str(), 'The content of the string is NOT as we expected ...', TRUE);
   } // method testGetExistingContent
   
   public function testBasicOperations()
   {
      $strObj = $this->getTestInstance();
      $this->assertFalse($strObj->isBlank(), 'The string-object was considered blank when it should not ...');
      
      // Try clear the string-content afterwords it should be empty.
      $strObj->clear();
      $this->assertTrue($strObj->isBlank(), 'The isBlank service-method should return true, if the string-content is empty ...');
      $this->assertEmpty($strObj->getAttr_str(), 'After using the clear-function the string-object should be empty ...');
      
      // Now try adding only blanks and test again, if its empty.
      $strContentToAdd = 'COOL!';
      $strObj->addToContent($strContentToAdd);
      $this->assertFalse($strObj->isBlank(), 'The isBlank service-method should return false, when the string-content contain something ...');

      // Okay its not empty, check to see if the content is the one you asked for.
      $this->assertEquals($strContentToAdd, $strObj->getAttr_str(), 'The content of the string-object was NOT set as expected ...');

      // Try use the lower-case
      $this->assertEquals('cool!', $strObj->getLowercase(), 'The getLowercase method should return the string-content in all lowercase ...');
   } // method TestBasicOperations

   public function testSubstrings()
   {
      $strObj = $this->getTestInstance();
      $arrCouples = $strObj->getSubStrings(',');

      $this->assertCount(6, $arrCouples, 'There should have been 6 couples in the array ...');
   } // method testSubstrings

} // End class