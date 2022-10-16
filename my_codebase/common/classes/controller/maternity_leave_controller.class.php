<?php
namespace Common\Classes\Controller;

use Common\Classes\Controller\StdController as StdController;
use Common\Classes\Controller\StdControllerInterface as StdControllerInterface;

// require_once(PATH_COMMON_CLASSES .'custom_datetime.class.php');
// require_once(PATH_COMMON_CLASSES .'time_period.class.php');

class MaternityLeaveController extends StdController implements StdControllerInterface
{
    // Attributes
    protected $maternityLeaveObj;

    // Methods
    
    /**
     * @param string $p_lang Default 'da'
     * @param string $p_charset Default 'utf8'
     */
    public function __construct(string $p_lang ='da', string $p_charset ='utf8') {
       // Set the attributes of the super-class
       parent::__construct($p_lang, $p_charset);
    }

    public function __destruct() {
       parent::__destruct();
    }

    /**
     * @param string $p_lang Default 'da'
     * @param string $p_charset Default 'utf8'
     *
     * @return MaternityLeaveController
     */
    public static function getInstance(string $p_lang ='da', string $p_charset ='utf8') : MaternityLeaveController {
       return new MaternityLeaveController($p_lang, $p_charset);
    }

    /**
     * @return void
     */
    public function handleCalcPeriod() : void {
       if (self::isRequestMethod_POST()) {
         echo 'Lets calculate and display'. PHP_EOL;
         /*$p_startDateTime, $p_babyBirthDateTime*/
       } else {
         // Display
         echo 'Hello World Baby!'. PHP_EOL;
       }
    }
/*
require_once(PATH_COMMON_CLASSES .'custom_datetime.class.php');
require_once(PATH_COMMON_CLASSES .'time_period.class.php');

 // Graviditetsorlov - Graviditetsorloven påbegyndes 4 uger før forventet fødsel til 14 uger efter fødselen.
 $customDateTime = CustomDateTime::getInstance();
 $fromDateTime = $customDateTime->getInstance_dateTime();

 Calc dates for period 1 
 
 // First period: Graviditetsorlov
 $fromDateTime = new DateTime();
 // $fromDateTime->setDate(2020, 9, 8); // Sygmeldt den 2020-09-08 - Dato for start før fødselen

 $fromDateTime->setDate(2020, 11, 9); // Dato for fødselen
 
 $toDateTime = CustomDateTime::getInstance();
 $toDateTime->setDate(2020, 11, 9);
 $toDateTime->modify("+1 day +46 weeks");
 
 echo PHP_EOL .'Start på arbejde efter barsel fra den: '. $dateTimeObj->format("d-m-Y");
 echo PHP_EOL .'Det er baseret på en beregning på 46 uger fra dagen efter fødslen den 10-11-2020.'. PHP_EOL;
 */
} // End class