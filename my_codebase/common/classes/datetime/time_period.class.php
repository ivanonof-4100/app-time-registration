<?php
namespace Common\Classes\Datetime;

use Common\Classes\Datetime\CustomDateTime;
use Common\Classes\CustomString;

/**
 * Filename     : time_period.class.php
 * Language     : PHP v7.4, v7.2+
 * Date created : 27/11-2020, Ivan
 * Last modified: 27/11-2020, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * Description:
 *  This class wraps class actions for handling a time-periods named or unamed.
 *  For named-periods I use my custom string-class that easily handles multi-byte strings like UTF-8.
 */
class TimePeriod
{
    /**
     * @var CustomDateTime
     */
    protected $fromDate;

    /**
     * @var CustomDateTime
     */
    protected $toDate;
    /**
     * @var CustomString $periodName
     */
    protected $periodName;

    /**
     * @param CustomDateTime $p_fromDate
     * @param CustomDateTime|string $p_toDate
     * @param string $p_periodName Default blank.
     */
    public function construct($p_fromDate, $p_toDate, $p_periodName ='') {
        $this->setAttr_fromDate($p_fromDate);
        // toDate
        $this->setAttr_toDate($p_toDate);
        $this->setAttr_periodName($p_periodName);
    }

    /**
     * @return string
     */
/*    
    public function __toString() : string {
        $customDateTime_fromDate = $this->getAttr_fromDate();
        $customDateTime_toDate = $this->getAttr_toDate();
        return sprintf('%s - (%s - %s)', $this->getAttr_periodName(), $customDateTime_fromDate, $customDateTime_toDate);
    }
*/
    /**
     * @return string
     */
    public function __toString() : string {
       $fromDate = $this->getAttr_fromDate();
       $fromDateTime = $fromDate->getInstance_dateTime();

       $toDate = $this->getAttr_toDate();
       $toDateTime = $toDate->getInstance_dateTime();

       if ($this->periodName->isBlank()) {
         // Un-named period
         return sprintf("%s - %s", $fromDateTime->format("d-m-Y"), $toDateTime->format("d-m-Y"));
       } else {
         // Named period
         return sprintf("%s : %s - %s", $this->getAttr_periodName(), $fromDateTime->format("d-m-Y"), $toDateTime->format("d-m-Y"));
       }
    } // method __toString

    public static function getInstance($p_fromDate, $p_toDate, $p_periodName ='') : TimePeriod {
        return new TimePeriod($p_fromDate, $p_toDate, $p_periodName);
    }

    // Setter and getter-methods

    protected function setAttr_fromDate($p_fromDate) : void {
        $this->fromDate = $p_fromDate;
    }

    public function getAttr_fromDate() {
        return $this->fromDate;
    }

    /**
     * @param $p_toDate CustomDateTime|string
     */
    protected function setAttr_toDate($p_toDate) : void {
        if (is_string($p_toDate)) {
          // Okay we need to extend the fromDate relatively.
          $this->toDate = $this->getAttr_toDate();  
        } else {
          // Use date as given
          $this->toDate = $p_toDate;
        }
    }

    /**
     * @return CustomDateTime
     */
    public function getAttr_toDate() : CustomDateTime {
        return $this->toDate;
    }

    /**
     * @param string $p_periodName Default blank.
     */
    public function setAttr_periodName(string $p_periodName ='') : void {
        $this->periodName = new CustomString($p_periodName, 'UTF-8');
    }

    /**
     * @return string
     */
    public function getAttr_periodName() : string {
        return $this->periodName->getUppercase();
    }

    public function displayPeriod() {
        echo $this->__toString();
    }
} // End class