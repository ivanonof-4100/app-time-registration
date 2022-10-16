<?php
/** 
 * Filename     : contact_request_message.class.php
 * Language     : PHP v5.x+, 7+
 * Date created : 06/11-2016, Ivan
 * Last modified: 13/11-2016, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2016 by Ivan Mark Andersen
 * 
 * Description:
 *  Model-class for handling contact-requests.
 */
require_once(PATH_COMMON_CLASSES .'custom_datetime.class.php');
require_once(PATH_COMMON_MODEL .'std_content.class.php');

class ContactRequestMessage extends StdContent
{
  // Attributes
  private $contact_request_id;
  private $contact_person_name;
  private $contact_person_phone;
  private $contact_person_email;
  private $contact_request_message;
  private $contact_request_date;
  private $request_remote_ip;

  // Methods

  /**
   * Default Constructor
   * 
   * @param int|bool $p_contactRequestId Default FALSE.
   * @param string|bool $p_contactPersonName = Default FALSE.
   * @param string|bool $p_contactPhone = Default FALSE.
   * @param string $p_contactPersonEmail
   * @param string $p_contactMessage
   * @param string $p_contactRequestDate
   *
   * @return ContactRequestMessage
   */
  public function __construct($p_contactRequestId =FALSE,
                              $p_contactPersonName =FALSE,
                              $p_contactPhone =FALSE,
                              $p_contactPersonEmail =FALSE,
                              $p_contactRequestMessage ='',
                              $p_contactRequestDate =FALSE)
  {
     parent::__construct();

     // contact_request_id
     if ($p_contactRequestId === FALSE) {
       $this->setAttr_contactRequestId();
       // Default 
     } else {
       // Use the argument as is.
       $this->setAttr_contactRequestId($p_contactRequestId);
     }

     // Name
     if ($p_contactPersonName) {
       $this->setAttr_contact_person_name($p_contactPersonName);
     } else {
       $this->setAttr_contact_person_name();
     }

     // Phone
     if ($p_contactPhone) {
       $this->setAttr_contact_person_phone($p_contactPhone);
     } else {
       $this->setAttr_contact_person_phone();
     }

     // E-mail
     if ($p_contactPersonEmail) {
       $this->setAttr_contact_person_email($p_contactPersonEmail);
     } else {
       // Use default.
       $this->setAttr_contact_person_email();
     }

     // Message
     if (!empty($p_contactRequestMessage)) {
       // Default something.
       $this->setAttr_contact_request_message();
     } else {
       $this->setAttr_contact_request_message($p_contactRequestMessage);
     }

//     if ($)
     
  } // method __construct

  /**
   * Default destructor of the class.
   */
  public function __destruct()
  {
     parent::__destruct();
  } // method __destruct

  // Getter and setter functions goes here.

  /**
   * @param int $p_contactRequestId Default 0.
   */
  private function setAttr_contact_request_id($p_contactRequestId =0)
  {
     $this->contact_request_id = (int) $p_contactRequestId;
  } // method setAttr_contact_request_id

  /**
   * @return int
   */
  public function getAttr_contact_request_id()
  {
     $this->contact_request_id;
  } // method getAttr_contact_request_id

  /**
   * @return int
   */
  public function getId()
  {
     return $this->getAttr_contact_request_id();
  } // method getId

  /**
   * Sets the attribute of the first-name of the instance.
   * @param string $p_name Default blank
   */
  private function setAttr_contact_person_name($p_name ='')
  {
     $this->contact_person_name = (string) ucfirst(trim($p_name));
  } // method setAttr_contact_person_name

  /**
   * Returns the name of the person of the instance.
   * @return string
   */
  public function getAttr_contact_person_name()
  {
     return $this->contact_person_name;
  } // method getAttr_contact_person_name

  /**
   * Sets the attribute of the phone of the contact-instance.
   * @param string $p_phone Default blank.
   */
  private function setAttr_contact_person_phone($p_phone ='')
  {
     $this->contact_person_phone = (string) $p_phone;
  } // method setAttr_contact_person_phone

  /**
   * Returns the phone of the instance.
   * @return string
   */
  public function getAttr_contact_person_phone()
  {
     return $this->contact_person_phone;
  } // method getAttr_contact_person_phone

  /**
   * Sets the attribute of the e-mail of the contact-instance.
   * @param string $p_email Default blank.
   */
  private function setAttr_contact_person_email($p_email ='')
  {
     $this->contact_person_email = (string) trim($p_email);
  } // method setAttr_contact_person_email

  /**
   * Returns the e-mail of the contact-request instance.
   * @return string
   */
  public function getAttr_contact_person_email()
  {
     return $this->contact_person_email;
  } // method getAttr_contact_person_email
 
  private function setAttr_contact_request_message($p_contactMessage ='')
  {
     $this->contact_request_message = (string) $p_contactMessage;
  } // method setAttr_contact_request_message

  public function getAttr_contact_request_message()
  {
     return $this->contact_request_message;
  } // method getAttr_contact_request_message

  /**
   * @param string 
   */
  private function setAttr_contact_request_date($p_requestDate ='today')
  {
     if (empty($p_requestDate)) {
      // Use default.
      $this->contact_request_date = CustomDateTime::getInstance('now');
     } else {
      $this->contact_request_date = CustomDateTime::getInstance($p_requestDate);
     }
  } // method setAttr_contact_request_date
 
  /**
   * @return string
   */
  public function getAttr_contact_request_date()
  {
     return $this->contact_request_date->getFormatedDate();
  } // method getAttr_contact_request_date

  /**
   * @param string $p_remoteIp 
   */
  private function setAttr_request_remote_ip($p_remoteIp ='')
  {
     if (empty($p_remoteIp)) {
       $this->request_remote_ip = (string) $_SERVER['REMOTE_ADRESS'];
     } else {
       $this->request_remote_ip = (string) $p_remoteIp;
     }
  } // method setAttr_request_remote_ip

  /**
   * @return string
   */
  public function getAttr_request_remote_ip()
  {
     return $this->request_remote_ip;
  } // method getAttr_request_remote_ip
} // End class