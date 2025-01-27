<?php declare(strict_types=1);
namespace Tests;

use PHPUnit\Framework\TestCase;
use Common\Classes\CustomString;

/*
 Read more the manual:
 https://phpunit.de/manual/current/en/extending-phpunit.html
*/
final class CustomStringUnitTest extends Testcase
{
   private $testObj;

   public function __construct() {
      parent::__construct();
   }

   public function __destruct() {
   }

   public function setUp() : void {
      $this->testObj = CustomString::getInstance('Katja og René, Carmen og Ivan, Kristina og Rudi', 'UTF-8');
   }

   public function getTestInstance() : CustomString {
      return $this->testObj;
   }

   public function testInstanceType() : void {
      $strObj = $this->getTestInstance();
      // $this->assertTrue($strObj instanceof CustomString, "The test-instance was an instance of expected class ...");
      $this->assertInstanceOf(CustomString::class, $strObj, "The test-instance was not an instance of expected class ...");
   }
   
   public function testGetExistingContent() {
      $strObj = $this->getTestInstance();
      $this->assertContains('Carmen og Ivan', $strObj->getAttr_str(), 'The content of the string is NOT as we expected ...', TRUE);
   }

   public function testBasicOperations() {
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
   }

   public function testSubstrings() {
      $strObj = $this->getTestInstance();
      $arrCouples = $strObj->getSubStrings(',');

      $this->assertCount(6, $arrCouples, 'There should have been 6 couples in the array ...');
   }
} // End class