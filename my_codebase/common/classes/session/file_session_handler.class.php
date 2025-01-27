<?php
namespace Common\Classes\Session;

use Common\Classes\Session\StandardSessionHandler;
use SessionHandlerInterface;

/**
 * @name        : FileSessionHandler
 * @version     : v2.5
 * Date created : 05/11-2016
 * Date modified: 20/05-2023 
 * @author      : Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2023 by Ivan Mark Andersen
 *
 * Description:
 *  This class wraps native PHP session-handler operations on session-data.
 *  Since PHP version >= v5.4.0 PHP has had its own SessionHandler-class.
 *  @see: https://www.php.net/manual/en/class.sessionhandler.php
 */
class FileSessionHandler extends StandardSessionHandler implements SessionHandlerInterface
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
   * @var int
   */
  protected $session_status;

  /**
   * @var array
   */
  protected $session_options;

  /**
   * The class constructor.
   * @access public
   * 
   * @param int $p_sessionExpiresInSecs Default expire-time is 5400 seconds.
   * @param string $p_sessionName Default session-name 'PHPSESSID'
   */
  public function __construct(int $p_sessionExpiresInSecs =5400, string $p_sessionName ='PHPSESSID') {
    $sessionPath = '/tmp/';
    $secure = false;
    $httponly = false;
    if (php_sapi_name() == 'cli') {
      $sessionDomain = SITE_DOMAIN_NAME;
    } else {
      $sessionDomain = $_SERVER['SERVER_NAME'];
    }

    // Session configuration directives
    ini_set('session.gc_probability', 1);
    ini_set('session.use_strict_mode', 0);
    // Where the session-files are saved if not in DB.
    ini_set('session.save_path', $sessionPath);
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

    $this->session_options = array(
      'lifetime' => $p_sessionExpiresInSecs,
      'path' => $sessionPath,
      'domain' => $sessionDomain,
      'secure' => $secure,
      'httponly' => $httponly,
      'samesite' => 'Strict' /* 'None', 'Lax', 'Strict' */
    );
    
    // session_set_save_handler(array($this, 'open'), array($this, 'close'),/* array($this, 'read'), array($this, 'write'), array($this, 'destroy') */ array($this, 'gc'));
    // Set the session-cookie to expire in an half an hour by default.
    $this->setCacheExpire($p_sessionExpiresInSecs);
    $this->setName($p_sessionName);
  }

  public function __destruct() {
     session_write_close();
  }

  /**
   * Make a new instance of the session-handler.
   * @param int $p_expiresInSecs Default 5400 seconds = 1½ hour
   * @param string $p_sessionName Default 'PHPSESSID'
   * @return FileSessionHandler
   */
  public static function getInstance(int $p_expiresInSecs =5400, string $p_sessionName ='PHPSESSID') : FileSessionHandler {
     return new FileSessionHandler($p_expiresInSecs, $p_sessionName);
  }

  /**
   * Starts a session using cookie-based sessions.
   * NOTE: session_start() must be called before outputing anything to the browser.
   * @access public
   * @param string $p_resumeSessionId Default blank.
   * @return bool
   */
  public function start(string $p_resumeSessionId ='') : bool {
    if (!empty($p_resumeSessionId)) {
      // Resume to a given resume-session-id
      $this->setID($p_resumeSessionId);
      $this->setCookie($this->session_options);
      return @session_start();
    } else {
      $existingSessionId = session_id();
      // Check, if we have an existing session.
      if ($this->hasExistingSession() || !empty($existingSessionId)) {
        // We have an existing session, so lets resume using that session.
        $this->setID($existingSessionId);
        $this->setCookie($this->session_options);
        return @session_start();
      } else {
        $newSessionId = $this->generateSessionID();
        $this->setID($newSessionId);
        $this->setCookie($this->session_options);
        return @session_start();
      }
    }
  }

  public function stop() {
    $this->commit();
     // $this->unsetVars();
     // $sessId = $this->getID();
     // parent::destroy($sessId);
  }

  /**
   * Checks if we have an existing session
   * @return bool
   */
  public function hasExistingSession() : bool {
    return ($this->getStatus() == PHP_SESSION_ACTIVE);
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
       return $this->start();
      // return TRUE; // Session is allready started.
     }
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
  public function initializeSessionData(string $p_encodedSessionData ='') : bool {
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
   * @return int session expire timer.
   */
  public function getCacheExpire() : int {
    return session_cache_expire();
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
  public function setID(string $p_sessionId) : string {
    return session_id($p_sessionId);
  }

  /**
   * Update the current session-id with a newly generated one.
   * 
   * @access public
   * @param bool $p_delOldSessionFile
   * @return bool Returns TRUE on success otherwise FALSE.
   */
  public function regenerateID($p_delOldSessionFile =FALSE) : bool {
  	 return session_regenerate_id($p_delOldSessionFile);
  }

  /**
   * Return unique session-ID of the current session.
   * @access public
   * @return string current session-ID
   */
  public function getID() : string {
    return session_id();
  }

  /**
   * Get the current session module
   *
   * @access public
   * @return string current session module
   */
  public function getModule() : string {
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
   * @return string Returns the previous session-name
   */
  public function setName(string $p_sessionName ='PHPSESSID') : string {
     return session_name($p_sessionName);
  }

  /**
   * Returns the current session-name.
   *
   * @access public
   * @return string Return the current session-name.
   */
  public function getName() : string {
   	 return session_name();
  }

  /**
   * Write session data and end session.
   * @access public
   */
  public function commit() : void {
    session_commit();
  }

  /**
   * setCookie - Set the session-cookie parameters.
   *
   * @access public
   * @param array $p_arrSessionOptions
   */
  public function setCookie(array $p_arrSessionOptions) : void {
    session_set_cookie_params($p_arrSessionOptions);
  }
} // End class