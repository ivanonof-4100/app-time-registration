<?php
namespace Common\Classes\Datetime;

use DateTime;
use DateTimeZone;
use UnexpectedValueException;

/**
 * Script-name  : custom_datetime.class.php
 * Language     : PHP v7.4, v7.2, v5.x
 * Date created : 21/01-2014, Ivan
 * Last modified: 14/10-2022, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2014 by Ivan Mark Andersen
 *
 * Description:
 *  This class wraps and implements handling of dates and date-time strings.
 *
 * Example 1:
 *  $customDateTimeObj = CustomDateTime::getInstance();
 *  $dateTimeObj->setDate(2009, 1, 31);
 *  $dateTimeObj = $customDateTimeObj->getInstance_dateTime();
 *  $dateTimeObj->modify("+1 month");
 *
 *  echo $dateTimeObj->format("Y-n-j");
 *
 *  // Birthday example
 *  $birthdayDateTime = CustomDateTime::getInstance();
 *  $birthdayDateTime->setDate(1973, 10, 15);
 *  echo $birthdayDateTime;
*/
class CustomDateTime
{
   protected $useActualMonthLength;

   /**
    * UNIX-timestamp which is the amount of seconds elapsed since the date of 01/01-1970.
    * @var int
    */
   protected $currentTimestamp;

   /**
    * @var DateTime
    */
   protected $dateTimeObj;

   /**
    * @var DateTimeZone
    */
   protected $dateTimeZoneObj;

   /**
    * Default constructor.
    *
    * @param string $p_initDateTimeStr Default 'now'
    * @param string $p_timezone
    */
   public function __construct(string $p_initDateTimeStr ='now', string $p_timezone ='Europe/Copenhagen') {
      // Default timezone is set in the configuration-file for the current site.
      $this->setAttr_useActualMonthLength(TRUE);

      // First set the time-zone.
      $this->setTimezone($p_timezone);

      // Set current DateTime instance, lets start by using the current date and time.
      $this->dateTimeObj = new DateTime($p_initDateTimeStr, $this->getInstance_dateTimeZone());
   }

   /**
    * Default destructor.
    */
   public function __destruct() {
   }

   /**
    * Magic method that is called when an instance is to be converted into a string like in a sprintf('%s')
    * @return string
    */
   public function __toString() : string {
      $dateTimeZone = $this->getInstance_dateTimeZone();
      $outputFormat = 'd/m-Y';
      return sprintf("%s (%s)".PHP_EOL, $this->dateTimeObj->format($outputFormat), $dateTimeZone->getName());
   }

   public static function getInstance($p_initDateTimeStr ='now', string $p_timezone ='Europe/Copenhagen') : CustomDateTime {
      return new CustomDateTime($p_initDateTimeStr, $p_timezone);
   }

   /**
    * Returns the contained date-time instance. 
    * @return DateTime
    */
   public function getInstance_dateTime() : DateTime {
      return clone $this->dateTimeObj;
   }

   /**
    * @return string
    */
   public static function getISODateFormat() : string {
      return 'Y-m-d';
   }

   /**
    * Parse formated string with date-values and generate a DateTime instance.
    *
    * @param string $p_formatedDate '2022-09-30';
    * @throws UnexpectedValueException
    * @return DateTime
    */
   public static function getDateTimeInstance_fromFormatedDate(string $p_formatedDate ='') : DateTime {
      $dateTimeInstance = DateTime::createFromFormat('!Y-m-d', $p_formatedDate); // Notice the ! to prevent formated-time
      if (!$dateTimeInstance instanceof DateTime) {
        throw new UnexpectedValueException(sprintf("Could not parse the date: '%s'", $p_formatedDate));
      } else {
        return $dateTimeInstance;
      }
   }

   /**
    * Returns the contained time-zone instance.
    * @return DateTimeZone
    */
   public function getInstance_dateTimeZone() : DateTimeZone {
      return $this->dateTimeZoneObj;
   }

   /**
    * Validates the given Gregorian-date.
    *
    * @param int $p_year
    * @param int $p_month
    * @param int $p_day
    *
    * @return bool
    */
   public static function isValidDate($p_year, $p_month, $p_day) : bool {
      return checkdate($p_month, $p_day, $p_year);
   }

   /**
    * @return string
    */
   public function getAttr_datePresentationFormat() : string {
      return 'd. F Y';
   }

   /**
    * @param string $p_timezone Default boolean FALSE which defaults to the default timezone.
    */
   public function setTimezone(string $p_timezone ='Europe/Copenhagen') : void {
      // Set time-zone to use.
      if ($p_timezone) {
        // Use the given time-zone.
        $this->setDefaultTimeZone($p_timezone);
        $this->dateTimeZoneObj = new DateTimeZone($p_timezone);
      } else {
        // Lets use the default timezone.
        $configuredTimeZone = ini_get('date.timezone');
        $this->setDefaultTimeZone($configuredTimeZone);
        // $this->dateTimeZoneObj = new DateTimeZone(date_default_timezone_get());
      }
   }

   /**
    * @return DateTimeZone
    */
   public function getTimezone() : DateTimeZone {
      return $this->dateTimeZoneObj;
   }

   /**
    * Sets the default timezone used by all date/time functions.
    * @param string $p_timezone
    */
   public function setDefaultTimeZone(string $p_timezone ='Europe/Copenhagen') : void {
      date_default_timezone_set($p_timezone);
   }

   /**
    * @return string
    */
   public function getFormatedDate(string $p_customFormat ='') : string {
      if (empty($p_customFormat)) {
        return $this->dateTimeObj->format($this->getAttr_datePresentationFormat());
      } else {
        return $this->dateTimeObj->format($p_customFormat);
      }
   }

   /**
    * @param string|boolean $p_customFormat Default boolean FALSE.
    * @return string
    */
   public function getFormatedDatetime($p_customFormat =FALSE) : string {
      if (!$p_customFormat) {
        return $this->dateTimeObj->format('Y-m-d H:i:s');
      } else {
        return $this->dateTimeObj->format($p_customFormat);
      }
   }

   /**
    * Returns the year of the current instance as an integer.
    * @return int
    */
   public function getYearNumber() : int {
      $year = (int) $this->dateTimeObj->format('Y');
      return $year;
   }

   /**
    * Returns the month as an integer.
    * @return int
    */
   public function getMonthNumber() : int {
      $month = (int) $this->dateTimeObj->format('m');
      return $month;
   }

   /**
    * Returns the ISO-8601 week-number of year, weeks starting on Monday
    * @return int
    */
   public function getWeekNumber() : int {
      $weekNumber = (int) $this->dateTimeObj->format('W');
      return $weekNumber;
   }

   /**
    * @return int
    */
   public function getDayOfMonth_asNumber() : int  {
      $dayOfMonth = (int) $this->dateTimeObj->format('d');
      return $dayOfMonth;
   }

   /**
    * Returns the ISO-8601 numeric representation of the day of the week - 1 (for Monday) through 7 (for Sunday).
    * @return int
    */
   public function getDayOfWeek_asNumber() : int {
      $dayOfWeek = (int) $this->dateTimeObj->format('N');
      return $dayOfWeek;
   }

   /**
    * Sets the date of the current DataTime to the first day of the week.
    * @param int $p_year
    */
   public function setDate_toYearStart(int $p_year =0) : void {
      if ($p_year == 0) {
         $p_year = $this->getYearNumber();
      }

      $this->dateTimeObj = $this->dateTimeObj->setDate($p_year, 1, 1);
   }

   public function setDate_toYearEnd(int $p_year =0) : void {
      if ($p_year == 0) {
         $p_year = $this->getYearNumber();
      }

      $this->dateTimeObj = $this->dateTimeObj->setDate($p_year, 12, 31);
   }

   /**
    * Sets the date of the current DataTime to the first day of the week.
    *
    * @param int $p_year
    * @param int $p_weekNumber
    */
   public function setDate_toWeekStart(int $p_year, int $p_weekNumber) : void {
      $this->dateTimeObj = $this->dateTimeObj->setISODate($p_year, $p_weekNumber, 1);
   }

   /**
    * @param int $p_year
    * @param int $p_weekNumber
    */
   public function setDate_toWeekEnd(int $p_year, int $p_weekNumber) : void {
      $this->dateTimeObj = $this->dateTimeObj->setISODate($p_year, $p_weekNumber, 7);
   }

   /**
    * @param int $p_year
    * @param int $p_weekNumber
    */
   public function setDateTime_toWeekStart(int $p_year, int $p_weekNumber) : void {
      $this->dateTimeObj->setISODate($p_year, $p_weekNumber, 1);
      $this->setTime(0, 0, 0);
   }

   /**
    * @param int $p_year
    * @param int $p_weekNumber
    */
   public function setDateTime_toWeekEnd(int $p_year, int $p_weekNumber) : void {
      $this->dateTimeObj = $this->dateTimeObj->setISODate($p_year, $p_weekNumber, 7);
      $this->setTime(23, 59, 59);
   }

   /**
    * Sets the current date by specifying all tree parameters - Year, Month and day of the month.
    *
    * @param int $p_year
    * @param int $p_month
    * @param int $p_dayOfMonth
    * @return void
    */
   public function setDate(int $p_year, int $p_month, int $p_dayOfMonth) : void {
      $this->dateTimeObj->setDate($p_year, $p_month, $p_dayOfMonth);
   }

   /**
    * setTime
    *
    * @param int $p_hour
    * @param int $p_minute
    * @param int $p_second
    * @return void
    */
   public function setTime(int $p_hour =0, int $p_minute =0, int $p_second =0) : void {
      $this->dateTimeObj->setTime($p_hour, $p_minute, $p_second);
   }

   /**
    * @param string $p_extendBy Default '+ 1 day'
    * @return DateTime
    */
   public function getExtended_dateTimeInstance($p_extendBy ='+1 day') : DateTime {
      $curDateTime = clone $this->getInstance_dateTime();
      $curDateTime->modify($p_extendBy);
      return $curDateTime;
   }

   /**
    * Sets whether or not to use the actual month-length insted of the standard 30 days.
    * @param bool $p_useActualMonthLength - Default FALSE.
    */
   private function setAttr_useActualMonthLength($p_useActualMonthLength =FALSE) : void {
      $this->useActualMonthLength = (boolean) $p_useActualMonthLength;
   }

   /**
    * Returns a boolean result on whether or not to use the actual month-length.
    * @return bool TRUE or FALSE
    */
   private function getAttr_useActualMonthLength() : bool {
      return $this->useActualMonthLength;
   }

   /**
    * @return void
    */
   public function refreshTimestamp() : void {
      $this->setAttr_currentTimestamp();
   }

   private function setAttr_currentTimestamp($p_timestamp =false) : void {
      if ($p_timestamp) {
        $this->currentTimestamp = (int) $p_timestamp;
      } else {
        // Default to the current UNIX-timestamp.
        $this->currentTimestamp = (int) time();
      }
   }

   /**
    * Returns an UNIX-timestamp.
    * @return int
    */
   private function getAttr_currentTimestamp() : int {
      return $this->currentTimestamp;
   }

   /**
    * Returns the number of weeks in the specified year.
    * @return int
    */
   public function getNumberOf_annualWeeks() : int {
      if ($this->isLongYear()) {
        $numberOfWeeks = (int) 53;
      } else {
        $numberOfWeeks = (int) 52;
      }
      return $numberOfWeeks;
   }

   /**
    * @return bool Checks if the current year of the DateTime-instance is a long-year.
    */
   public function isLongYear() : bool {
      return self::wasLongYear($this->getYearNumber());
   }

   /**
    * @return bool Checks if the current year of the instance is a leap-year.
    */
   public function isLeapYear() : bool {
      return self::wasLeapYear($this->getYearNumber());
   }

   /**
    * Long years are the years with 53 weeks in them instead of just 52 weeks.
    * @return bool Returns boolean TRUE if the given year is a long-year otherwise FALSE.
    */
   public static function waslongYear(int $p_yearNumber) : bool {
      $dateStr = sprintf('%d-%02d-%02d', $p_yearNumber, 1, 1);
      $dateTimeObj_firstDayOfYear = new DateTime($dateStr);

      if (self::wasLeapYear($p_yearNumber)) {
        // Any leap-year that starts on an wednesday.
        return ($dateTimeObj_firstDayOfYear->format('N') == 3);
      } else {
        // Any year that starts on a thursday.
        return ($dateTimeObj_firstDayOfYear->format('N') == 4);
      }
   }

   /**
    * Checks if the given year was a leap-year and returns TRUE if so, otherwise FALSE.
    *
    * A year is a leap-year when the year-number is dividable by 4,
    * but not if the year-number also is dividable by 100 aside from the years dividable by 400 which is a leap-year.
    * An example is the year 1900 was NOT a leap-year, but the year 2000 was.
    *
    * @param int $p_yearNumber
    * @return bool
    */
   public static function wasLeapYear(int $p_yearNumber) : bool {
      return ((($p_yearNumber %4 == 0) && !($p_yearNumber %100 == 0)) || ($p_yearNumber %400 == 0));
   }

   /**
    * Calculates the difference between two given dates.
    * 
    * @param string $p_startDate
    * @param string $p_endDate Default 'today'
    * 
    * @return array
    */
   public static function calcDateDiff($p_startDate, $p_endDate ='today') {
      $startDateObj = new CustomDateTime($p_startDate);
      $endDateObj = new CustomDateTime($p_endDate);

      $startDate_dateTimeObj = $startDateObj->getInstance_dateTime();
      $endDate_dateTimeObj = $endDateObj->getInstance_dateTime();
      $diffDateIntervalObj = $startDate_dateTimeObj->diff($endDate_dateTimeObj);

      $arrDiff = array('years' => $diffDateIntervalObj->format('%y'), 'months' => $diffDateIntervalObj->format('%m'), 'days' => $diffDateIntervalObj->format('%d'));
      return $arrDiff;
   }
} // End class