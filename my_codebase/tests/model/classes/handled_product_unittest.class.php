<?php
use PHPUnit\Framework\TestCase;

/*
 Read more the manual:
 https://phpunit.de/manual/current/en/extending-phpunit.html
*/
require_once '/data/demo_codebase/config/api_headsetservice_dk.conf.php';
require_once PATH_COMMON_CONTROLLERS .'handled_product_controller.class.php';
require_once PATH_COMMON_MODEL .'handled_product.class.php';

class HandledProductUnitTest extends PHPUnit_Framework_Testcase
{
   private $testObj;
   private $handledProductControllerObj;

   /**
    * @return HandledProductUnitTest
    */
   public function __construct()
   {
      parent::__construct();
      $this->handledProductControllerObj = HandledProductController::getInstance();
   } // method __construct

   public function setup()
   {
      $this->testObj = HandledProduct::getInstance();
      $this->handledProductControllerObj->initDependencies();
   } // method setup

   public function getTestInstance()
   {
      return $this->testObj;
   } // method getTestInstance

   public function getControllerInstance()
   {
      return $this->handledProductControllerObj;
   } // method getControllerInstance

   public function testInstanceType()
   {
      $testObj = $this->getTestInstance();
      $this->assertInstanceOf('HandledProduct', $testObj, "The test-instance was not an instance of expected class ...");
   } // method testInstanceType

   public function testLookupBySerialNumber()
   {
      $handledProductControllerObj = $this->getControllerInstance();

      $testSerialNumber = '0060001622';
      $handlingId = HandledProduct::doesSerialNumberExists($testSerialNumber, $handledProductControllerObj);
      if ($handlingId) {
       $wasFound = TRUE;
       // Get the instance of the model-object.
       $handledProductObj = HandledProduct::getInstance_byObjId($handlingId, $handledProductControllerObj);
   
       $this->assertInstanceOf('HandledProduct', $handledProductObj, "The test-instance was not an instance of expected class ...");
       
       $this->assertEquals($handlingId, $handledProductObj->getId(), 'The ID of the object was NOT set as expected ...');
      } else {
       $wasFound = FALSE;
       $handledProductObj = null;
      }
   } // method testLookupBySerialNumber

} // End class