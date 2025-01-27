<?php
namespace Common\Classes\Model;

use Common\Classes\Model\StdModel;
use Common\Classes\Datetime\CustomDateTime;

/** 
 * Filename     : person.class.php
 * Language     : PHP v7.x
 * Date created : 11/02-2012, Ivan
 * Last modified: 09/11-2012, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright: Copyright (C) 2012 by Ivan Mark Andersen
 * 
 * Description:
 *  My abstract person content-class.
 */
abstract class Person extends StdModel
{  
  const HUMAN_GENDER_FEMALE = 'F';
  const HUMAN_GENDER_MALE = 'M';

  // Attributes
  protected $person_first_name;
  protected $person_middle_name;
  protected $person_last_name;

  /**
   * A representation of which gender the person is.
   * @var string 'F' for Female or 'M' for Male
   */
  protected $person_human_gender;

  protected $person_birthday;

  // Methods

  /**
   * Default Constructor
   * 
   * @param string $p_humanGender Default 'M'
   * @param string|bool $p_firstName Default FALSE
   * @param string|bool $p_middleName Default FALSE
   * @param string|bool $p_lastName Default FALSE
   * @param string|bool $p_dateOfBirth Default blank.
   * 
   * @return Person
   */
  public function __construct(string $p_humanGender =self::HUMAN_GENDER_MALE, $p_firstName =FALSE, $p_middleName =FALSE, $p_lastName =FALSE, $p_dateOfBirth ='') {
     parent::__construct();

     // First-name
     if ($p_firstName) {
       $this->setAttr_person_first_name($p_firstName);
     } else {
       $this->setAttr_person_first_name();
     }

     // Middle-name
     if ($p_middleName) {
       $this->setAttr_person_middle_name($p_middleName);
     } else {
       $this->setAttr_person_middle_name();
     }

     // Last-name
     if ($p_lastName) {
       $this->setAttr_person_last_name($p_lastName);
     } else {
       $this->setAttr_person_last_name();
     }

     // Birthday
     $this->setAttr_person_birthday($p_dateOfBirth);

     // Human gender
     if ($p_humanGender) {
       $this->setAttr_person_human_gender($p_humanGender);
     } else {
       $this->setAttr_person_human_gender();
     }
  }

  /**
   * Default destructor of the class.
   */
  public function __destruct() {
     parent::__destruct();
  }

  /**
   * @return string
   */
  public function __toString() : string {
     return sprintf("%s : %s", $this->getAttr_person_human_gender(), $this->getFullName());
  }

  // Getter and setter methods

  /**
   * Sets the attribute of the first-name of the instance.
   * @param string $p_firstName Default blank
   */
  protected function setAttr_person_first_name($p_firstName ='') : void {
     $this->person_first_name = (string) ucfirst(trim($p_firstName));
  }

  /**
   * Returns the first-name of the person of the instance.
   * @return string
   */
  protected function getAttr_person_first_name() : string {
     return $this->person_first_name;
  }

  /**
   * Sets the attribute of the first-name of the instance.
   * @param string $p_middleName Default blank.
   */
  protected function setAttr_person_middle_name(string $p_middleName ='') : void {
     $this->person_middle_name = (string) ucfirst(trim($p_middleName));
  }

  /**
   * Returns the middle-name of the person of the instance.
   * @return string
   */
  protected function getAttr_person_middle_name() : string {
     return $this->person_middle_name;
  }

  /**
   * Sets the attribute of the last-name of the instance.
   * @param string $p_lastName Default blank.
   */
  protected function setAttr_person_last_name(string $p_lastName ='') : void {
     $this->person_last_name = (string) ucfirst(trim($p_lastName));
  }

  /**
   * Returns the last-name of the person of the instance.
   * @return string
   */
  protected function getAttr_person_last_name() : string {
     return $this->person_last_name;
  }

  /**
   * @return string
   */
  public function getFullName() : string {
      if ($this->hasMiddleName()) {
        return printf('%s %s %s', $this->getAttr_person_first_name(), $this->getAttr_person_middle_name(), $this->getAttr_person_last_name());
      } else {
        return printf('%s %s', $this->getAttr_person_first_name(), $this->getAttr_person_last_name());
      }
  }

  /**
   * Sets the date of birth of the person of the instance.
   */
  protected function setAttr_person_birthday($p_dateOfBirth ='') : void {
     $this->person_birthday = (string) $p_dateOfBirth;
  }

  /**
   * Returns the value of the attribute date of birth of the person of the instance.
   * @return string
   */
  protected function getAttr_person_birthday() {
     return $this->person_birthday;
  }

  /**
   * Sets the attribute of the instance that tells the gender of the person.
   * @param string $p_humanGender
   */
  protected function setAttr_person_human_gender(string $p_humanGender = self::HUMAN_GENDER_MALE) {
     $this->person_human_gender = (string) $p_humanGender;
  }

  /**
   * Returns the attribute of the instance that tells of what gender the person is.
   * @return string
   */
  public function getAttr_person_human_gender() : string {
     return $this->person_human_gender;
  }

  /**
   * Returns the first-name of the person of the instance.
   * @return string
   */
  public function getFirstName() : string {
     return $this->getAttr_person_first_name();
  }

  /**
   * Returns the middle-name of the person of the instance.
   * @return string
   */
  public function getMiddleName() : string {
     return $this->getAttr_person_middle_name();
  }

  /**
   * Returns the last-name of the person of the instance.
   * @return string
   */
  public function getLastName() : string {
     return $this->getAttr_person_last_name();
  }

  /**
   * Checks if the person of the instance has a middle-name.
   * @return bool Returns TRUE if has a middle-name otherwise FALSE.
   */
  public function hasMiddleName() : bool {
     $middleName = $this->getAttr_person_middle_name();
     return (!empty($middleName));
  }

  /**
   * Returns the date of birth of the person of the instance.
   * @return string Like '1973-10-15'
   */
  public function getDateOfBirth() {
     return $this->getAttr_person_birthday();
  }

  /**
   * Returns the calculated current age of the person of the instance.
   * @return int
   */
  public function getCurrentAge() : int {
     // Calculate the age of the person.
     $arrDiffResult = CustomDateTime::calcDateDiff($this->getAttr_person_birthday());
     if (is_array($arrDiffResult)) {
       if (array_key_exists('years', $arrDiffResult)) {
         $currentAge = (int) $arrDiffResult['years'];
       } else {
         $currentAge = (int) 0;
       }
     } else {
       $currentAge = (int) 0;
       trigger_error(__METHOD__ .': The expected result-array was not an array ...', E_USER_ERROR);
     }

     return $currentAge;
  }

  /**
   * @return string Returns 'F' or 'M'.
   */
  public function getHumanGender() : string {
     return $this->getAttr_person_human_gender();
  }

  /**
   * Returns a boolean-result based on whether or not the person of the instance is female.
   * @return bool TRUE if female otherwise FALSE.
   */
  public function isFemale() : bool {
     return ($this->getAttr_person_human_gender() == self::HUMAN_GENDER_FEMALE);
  }

  /**
   * Returns a boolean-result based on whether or not the gender of the person of the instance is male.
   * @return boolean TRUE if male otherwise FALSE.
   */
  public function isMale() : bool {
     return ($this->getAttr_person_human_gender() == self::HUMAN_GENDER_MALE);
  }
} // End class