<?php
namespace Common\Classes;
use SessionHandler;

/**
 * @name        : CustomSessionHandler
 * @version     : v2.0
 * Date created : 05/11-2016
 * Date modified: 07/01-2023 
 * @author      : Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2016 by Ivan Mark Andersen
 *
 * Description:
 *  This class wraps native PHP session-handler operations on session-data.
 *  Since PHP version >= v5.4.0 PHP has its own SessionHandler class.
 *  This custom version will work since there is no naming-conflicts with PHP-classes.
 */
class CustomSessionHandler extends SessionHandler
{
  /**
   * @var string
   */
  protected $session_path;

  /**
   * @var int
   */
  protected $session_lifetime;

  /**
   * The class constructor.
   * @access public
   * 
   * @param int $p_sessionExpiresInSecs Default expire-time is 1800 seconds.
   * @param string $p_sessionName Default session-name 'PHPSESSID'
   *
   * @return CustomSessionHandler
   */
  public function __construct(int $p_sessionExpiresInSecs =1800, string $p_sessionName ='PHPSESSID') {
    if (!$this->hasSessionStarted()) {
      $sessionPath = '/tmp/';
      $secure = false;
      $httponly = false;
      if (php_sapi_name() == 'cli') {
        $sessionDomain = SITE_DOMAIN_NAME;
      } else {
        $sessionDomain = $_SERVER['SERVER_NAME'];
      }

  	  // session_set_save_handler(array($this, 'start'), array($this, 'stop')/*, array($this, 'read'), array($this, 'write')*/, array($this, 'destroy'), array($this, 'gc'));
      ini_set('session.use_strict_mode', 0);
      ini_set('session.save_path', $sessionPath); // Where the session-files are saved if not in DB.
      // Specifies whether the session module starts a session automatically on request startup. Defaults to 0 (disabled). 
      ini_set('session.auto_start', 0);

      // Use session-cookies.
      ini_set('session.use_cookies', 1);

      // Session ID cannot be passed through URLs
      ini_set('session.use_only_cookies', 1);

      // Prevents javascript Cross-Site-Scripting (XSS) attacks aimed to steal the Session-ID and hijack the Session.
      ini_set('session.cookie_httponly', 1);

      // Use a strong hashing-algorithm.
      ini_set('session.hash_function', 'whirlpool');
      // Set the life-time of a session. 
      ini_set('session.gc_maxlifetime', $p_sessionExpiresInSecs);
      ini_set('session.gc_probability', 100);
      ini_set('session.gc_divisor', 100);

      // Set the session-cookie to expire in an half an hour by default.
      $arrSessionOptions = array(
        'lifetime' => $p_sessionExpiresInSecs,
        'path' => $sessionPath,
        'domain' => $sessionDomain,
        'secure' => $secure,
        'httponly' => $httponly,
        'samesite' => 'Strict' /* 'None', 'Lax', 'Strict' */
      );

      $this->setName($p_sessionName);
      $this->setCookie($arrSessionOptions);
    }
  }

  public function __destruct() {
     session_write_close();
  }

  /**
   * Make a new instance of the session-handler.
   * @return CustomSessionHandler
   */
  public static function getInstance($p_sessionExpiresInSecs =1800, $p_sessionName ='PHPSESSID') : CustomSessionHandler {
     return new CustomSessionHandler($p_sessionExpiresInSecs, $p_sessionName);
  }

  /**
   * Starts a session using cookie-based sessions.
   * NOTE: session_start() must be called before outputing anything to the browser.
   * @access public
   * @return bool
   */
  public function start() : bool {
     // Start new or resume existing session.
     return session_start();
  }

  public function stop() {
     $this->unsetVars();
     $sessId = $this->getID();
     parent::destroy($sessId);
  }

  /**
   * Encapsulates the activation of the session-instance.
   * @return bool
   */
  public function activateSession() : bool {
     if (!$this->hasSessionStarted()) {
       $newSessionID = $this->generateSessionID();
       $this->setID($newSessionID);
       return $this->start(); // Start the session, it has not yet been started.
     } else {
       return TRUE; // Session is allready started.
     }
  }

  /**
   * Returns the current session-status.
   * @return int
   *  PHP_SESSION_DISABLED if sessions are disabled.
   *  PHP_SESSION_NONE     if sessions are enabled, but none exists.
   *  PHP_SESSION_ACTIVE   if sessions are enabled, and one exists.
   */
  public function getStatus() {
     return session_status();
  }

  /**
   * Checks if the current session has started.
   * @return bool Returns boolean TRUE, if a session has started else FALSE.
   */
  public function hasSessionStarted() : bool {
    if (php_sapi_name() !== 'cli') {
      if (version_compare(phpversion(), '5.4.0', '>=')) {
        return ($this->getStatus() === PHP_SESSION_ACTIVE) ? TRUE : FALSE;
      } else {
        $currentSessionID = $this->getID();
        return empty($currentSessionID) ? FALSE : TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Generates a unique session-id.
   * @return string
   */
  public function generateSessionID() : string {
    if (function_exists('session_create_id')) {
      return session_create_id();
    } else {
      $newSessionID = $this->generateCustomSessionID();
      return $newSessionID;
    }
  }

  /**
   * @param int $p_numOfChars Default 26
   * @return string
   */
  public function generateCustomSessionID(int $p_numOfChars =26) : string {
      $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-';
      srand((double)microtime()*1000000);
      $idx = 0;
      $sessId = '';
      while ($idx < $p_numOfChars)
      {
        $num = mt_rand(0, strlen($chars));
        // $num = mt_rand(1, strlen($chars)) % 33;
        $tmp = substr($chars, $num, 1);
        $sessId = $sessId . $tmp;
        $idx++;
      }

      return $sessId;
  }

  /**
   * Checks if a given session-id is a valid session-id.
   *
   * @param string $p_sessionId
   * @return bool
   */
  public function isSessionIdValid($p_sessionId) : bool {
     return preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $p_sessionId) > 0;
  }

  /**
   * Decodes the serialized session-data provided to the method
   * and populates the $_SESSION superglobal with the result.
   *
   * @param string $p_encodedSessionData Default blank.
   * @return bool Returns TRUE on success or FALSE on failure.
   */
  public function initializeSessionData($p_encodedSessionData ='') : bool {
     return session_decode($p_encodedSessionData);
  }

  /**
   * Returns the current session-data as a session encoded-string.
   * Pre-condition: Make sure that the session_start function is called before.
   *
   * @return string Eg. login_ok|b:1;nome|s:4:"sica";inteiro|i:34;
   */
  public function getEncodedSession() : string {
     return session_encode();
  }

  /**
   * getCacheExpire - Return session expire time.
   *
   * @access public
   * @return integer session expire timer.
   */
  public function getCacheExpire() : int {
    return session_cache_expire();
  }

  /**
   * Set session expire time
   *
   * @access public
   * @param integer new session expire-time.
   * @return void
   */
  public function setCacheExpire(int $intTime) : void {
    session_cache_expire($intTime);
  }

  /**
   * Get the current cache limiter
   *
   * @access public
   * @return string current cache limiter
   */
  public function getCacheLimiter() : string {
    return session_cache_limiter();
  }

  /**
   * Set the cache limiter
   *
   * @access public
   * @param string $p_strLimiter new cache limiter
   * @return string old cache limiter (before this change)
   */
  public function setCacheLimiter($strLimiter) : string {
    return session_cache_limiter($strLimiter);
  }

  /**
   * Set session ID
   *
   * @access public
   * @param string $p_sessionId The new session-ID.
   * @return string Returns the old session-ID (before this change)
   */
  public function setID(string $p_sessionId) {
    return session_id($p_sessionId);
  }

  /**
   * Update the current session-id with a newly generated one.
   * 
   * @access public
   * @param boolean $p_delOldSessionFile
   * @return boolean Returns TRUE on success otherwise FALSE.
   */
  public function regenerateID($p_delOldSessionFile =FALSE) {
  	 return session_regenerate_id($p_delOldSessionFile);
  }

  /**
   * Return unique session-ID of the current session.
   * @access public
   * @return string current session-ID
   */
  public function getID() {
    return session_id();
  }

  /**
   * Get the current session module
   *
   * @access public
   * @return string current session module
   */
  public function getModule() {
    return session_module_name();
  }

  /**
   * Set the session module
   *
   * @access public
   * @param string new session module
   * @return string old session module (before this change)
   */
  public function setModule($strModule) {
    return session_module_name($strModule);
  }

  /**
   * Set the current session name
   *
   * @access public
   * @param string $p_sessionName The new session-name.
   * @return string Returns the old session-name (before this change)
   */
  public function setName($p_sessionName ='PHPSESSID') {
     return session_name($p_sessionName);
  }

  /**
   * Returns the current session-name.
   *
   * @access public
   * @return string Return the current session-name.
   */
  public function getName() {
   	 return session_name();
  }

  /**
   * Write session data and end session.
   * @access public
   */
  public function commit() {
    session_commit();
  }

  /**
   * setCookie - Set the session-cookie parameters.
   *
   * @access public
   * @param array $p_arrSessionOptions
   */
  public function setCookie(array $p_arrSessionOptions) {
    session_set_cookie_params($p_arrSessionOptions);
  }

  /**
   * Free all session variables
   * @access public
   */
  public function unsetVars() {
    session_unset();
  }

  /**
   * Set session variables
   *
   * @access public
   * @param mixed key or array (array('key'=>'value'))
   * @param mixed value or null when key is an array
   */
  public function set($mixKey, $mixValue =null) {
    if (is_array($mixKey)) {
      foreach ($mixKey as $key=>$val)
      {
        if (strlen($key)>0) {
          $_SESSION[$key] = $val;
        }
      }
    } else {
      if (strlen($mixKey)>0) {
        $_SESSION[$mixKey] = $mixValue;
      }
    }
  }

  /**
   * Return session variable
   *
   * @access public
   * @param string key naem
   * @return mixed value
   */
  public function get($strKey) {
    if (isset($_SESSION[$strKey])) {
      return $_SESSION[$strKey];
    } else {
      return null;
    }
  }
} // End class