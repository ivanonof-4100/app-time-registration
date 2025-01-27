<?php
namespace Common\Classes;
use Common\Classes\CustomString;

/**
 * Filename  : password.class.php 
 * Language     : PHP v7.4
 * Date created : 18/10-2012, Ivan
 * Last modified: 24/09-2022, Ivan
 * Author(s)    : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright Copyright (C) 2012 by Ivan Mark Andersen
 *
 * Description
 *  This class wraps the methods on a password as a non-persistent object.
 *  
 *  @example:
 *  
*/
class Password
{
  const CUSTOM_SALT_STR ='Fox8';

  private $password;

  /**
   * Default constructor.
   * 
   * @param string $p_passwd
   * @param boolean $p_isAllreadyEncrypted
   */
  public function __construct($p_passwd ='', $p_isAllreadyEncrypted =false) {
     if (empty($p_passwd)) {
       // Then generate a random password.
       $p_isAllreadyEncrypted = (boolean) false;
       $p_passwd = self::generateRandomPassword();
     }

     $this->setAttr_password($p_passwd, $p_isAllreadyEncrypted);
  }

  /**
   * Default destructor it destructs the memory object of an instance, when not used any more.
   */
  public function __destruct() {
  }

  /**
   * Sets the password attribute and makes sure that the password is encryted. 
   * 
   * @param string $p_passwd
   * @param boolean $p_isAllreadyEncrypted
   */
  private function setAttr_password($p_passwd, $p_isAllreadyEncrypted =false) {
     if ($p_isAllreadyEncrypted) {
       $this->password = (string) $p_passwd;
     } else {
       // Set and encrypt the password.
       $this->password = (string) self::encryptPassword($p_passwd);
     }
  }

  /**
   * @return string
   */
  public function getAttr_password() : string {
     return $this->password;
  }

  /**
   * Generates a random-password.
   *
   * @param int $p_keyLength Default 8
   * @return string
   */
  public static function generateRandomPassword($p_keyLength =8) {
    $strPossibleChars = CustomString::getInstance('abcdefghijklmnopqrstuvwxyz_.:-ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 'UTF-8');
    $randMaxPos = $strPossibleChars->getStringLengthOfInstance();
    for ($idx =0; $idx < $p_keyLength; $idx++) {
      $randPos = rand(0, $randMaxPos);
      $randKey[] = $strPossibleChars->getCharAtPos($randPos);
    }

    $randPasswd = implode('', $randKey);
    return $randPasswd;
  }

/*
  public static function generateRandomPassword($p_keyLength =9) {
     $keyChars = 'abcdefghijklmnopqrstuvwxyz_.:-ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
     $randMax = strlen($keyChars) -1;

     for ($idx =0; $idx < $p_keyLength; $idx++) {
       $randPos = rand(0, $randMax);
       $randKey[] = $keyChars{$randPos};
     }

     $randPasswd = implode('', $randKey);
     return $randPasswd;
  }
*/

  /**
   * Encrypts and return a given string.
   * Read: https://www.php.net/manual/en/function.crypt.php
   *
   * @param string $p_unincryptedPasswd
   * @return string
   */
  public static function encryptPassword($p_unincryptedPasswd) {
     return crypt($p_unincryptedPasswd, self::CUSTOM_SALT_STR);
  }

  /**
   * Does one-way authentication of the password.
   *
   * @param string $p_givenPasswd
   * @return bool Returns TRUE on succes and FALSE on failure.
   */
  public function authPassword($p_givenPasswd) : bool {
     /* Pass the entire results of crypt() as the salt for comparing a password,
      * to avoid problems when different hashing algorithms are used.
      * (As it says above, standard DES-based password hashing uses a 2-character salt, but MD5-based hashing uses 12.)
     */

     // Get the current password.
     $currentPassword = $this->getAttr_password();
     if (empty($p_givenPasswd) || empty($currentPassword)) {
       // It was not possible to make an authentication of the given password because one of the required parameters was empty!
       $authResult = (boolean) false;
     } else {
       if (crypt($p_givenPasswd, $currentPassword) == $currentPassword) {
         $authResult = (boolean) true;
       } else {
         $authResult = (boolean) false;
       }
     }

     return $authResult;
  }
} // End class