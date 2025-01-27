<?php
namespace Common\Classes\Session;
use SessionHandler;
// use SessionIdInterface;
use Common\Classes\Datetime\CustomDateTime;

/**
 * @name        : StandardSessionHandler
 * @version     : v1.2
 * Date created : 16/05-2023
 * Date modified: 15/06-2023 
 * @author      : Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2023 by Ivan Mark Andersen
 *
 * Description:
 * This class wraps native PHP session-handler operations on session-data.
 * PHP has had its own SessionHandler-class since PHP version >= v5.4.0.
 * @see: https://www.php.net/manual/en/class.sessionhandler.php
 */
class StandardSessionHandler extends SessionHandler /* implements SessionIdInterface */ {
  const SESS_ID_MAX_LENGTH = 32;
  const SESS_LIFETIME_DEFAULT =5400;

  /**
   * @var array
   */
  protected $session_options;

  /**
   * @var int
   */
  protected $session_lifetime;

  /**
   * The class constructor.
   * @access public
   * 
   * @param int $p_sessionExpiresInSecs Default expire-time is 5400 seconds.
   * @param string $p_savePath Default '/tmp/'
   * @param string $p_sessionName Default session-name 'PHPSESSID'
   */
  public function __construct(int $p_sessionExpiresInSecs =self::SESS_LIFETIME_DEFAULT,
                              string $p_savePath ='/tmp/',
                              string $p_sessionName ='PHPSESSID') {
    if (php_sapi_name() == 'cli') {
      $sessionDomain = SITE_DOMAIN_NAME;
    } else {
      $sessionDomain = SITE_DOMAIN_NAME;
    }

    $this->setSessionLifetime($p_sessionExpiresInSecs);
    $this->setName($p_sessionName);

    // Session configuration-directives
    // Specifies whether the session module starts a session automatically on request startup. Defaults to 0 (disabled) 
    // ini_set('session.auto_start', 1);
    // Use session-cookies.
    // ini_set('session.use_cookies', 1);
    // ini_set('session.cookie_lifetime', $p_sessionExpiresInSecs);
/*
    ini_set('session.use_strict_mode', 1);
    // Where the session-files are saved if not in DB.
    ini_set('session.save_path', $p_savePath);

    // ini_set('session.cookie_lifetime', 0); // Until browser is restarted
    // Session ID cannot be passed through URLs
    ini_set('session.use_only_cookies', 1);
    // Prevents javascript Cross-Site-Scripting (XSS) attacks aimed to steal the Session-ID and hijack the Session.
    ini_set('session.cookie_httponly', 1);
    // Use a strong hashing-algorithm.
    ini_set('session.hash_function', 'whirlpool');
    // Set the life-time of a session.
    ini_set('session.gc_maxlifetime', $p_sessionExpiresInSecs+60);
    ini_set('session.gc_probability', 100);
    ini_set('session.gc_divisor', 100);
*/

    if ($p_savePath == '') {
      $this->session_options = array(
        'lifetime' => $p_sessionExpiresInSecs,
        'domain' => $sessionDomain,
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Strict'
      );
    } else {
      $this->session_options = array(
        'lifetime' => $p_sessionExpiresInSecs,
        'path' => $p_savePath,
        'domain' => $sessionDomain,
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Strict'
      );
    }
  }

  public function __destruct() {
    // session_write_close();
  }

  /**
   * @param int Default 5400
   */
  protected function setSessionLifetime(int $p_sessionLifetime =self::SESS_LIFETIME_DEFAULT) {
    $this->session_lifetime = $p_sessionLifetime;
  }

  /**
   * @return int
   */
  public function getSessionLifetime() : int {
    return $this->session_lifetime;
  }

  /**
   * Generates a collision-free session-id.
   * @return string
   */
/*
  public function generateSessionId() : string {
    return session_create_id();
  }
*/

  /**
   * Generates a unique session-id.
   * This method is invoked internally when a new session-id is needed no parameter is needed.
   * 
   * @param int $p_byteLength Default 13 which will generate tokens 26-chars long.
   * @return string
   */
/*
  public function create_sid(int $p_byteLenght =13) : string {
    return bin2hex(random_bytes($p_byteLenght));
  }
*/

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
   * Return unique session-ID of the current session.
   * @access public
   * @return string current session-ID
   */
  public function getID() : string {
    return session_id();
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
   * Returns the current session-status.
   * @return int
   *  PHP_SESSION_DISABLED if sessions are disabled.
   *  PHP_SESSION_NONE     if sessions are enabled, but none exists.
   *  PHP_SESSION_ACTIVE   if sessions are enabled, and one exists.
   */
  public function getStatus() : int {
    return session_status();
  }

  /**
   * Checks if a session has started.
   * @return bool Returns boolean TRUE, if a session has started else FALSE.
   */
  public function hasSessionStarted() : bool {
    if (php_sapi_name() == 'cli') {
      return FALSE;
    } else {
      if (version_compare(phpversion(), '5.4.0', '>=')) {
        return ($this->getStatus() === PHP_SESSION_ACTIVE) ? TRUE : FALSE;
      } else {
        $existingSessionId = session_id();
        return empty($existingSessionId) ? FALSE : TRUE;
      }
    }
  }

  /**
   * Checks if we have an existing session
   * @return bool
   */
  public function hasExistingSession() : bool {
    if (php_sapi_name() == 'cli') {
      return FALSE;
    } else {
      return ($this->getStatus() === PHP_SESSION_ACTIVE) ? TRUE : FALSE;
    }
  }

  /**
   * Sets the session-cookie parameters.
   *
   * @param array $p_cookieOptions Default an empty array.
   * @return void
   */
  public static function setCookieParms(array $p_cookieOptions =[]) : void {
    session_set_cookie_params($p_cookieOptions);
  }

  /**
   * Setup run-time configuration for the session and session-cookie parameters.
   * @param int $p_sessionLifetime
   */
  public static function setupSession(int $p_sessionLifetime =self::SESS_LIFETIME_DEFAULT) : void {
    // Use session-cookies.
    ini_set('session.auto_start', 1);
    ini_set('session.lazy_write', 0);
    ini_set('session.cookie_lifetime', $p_sessionLifetime);
    // Session ID cannot be passed through URLs
    ini_set('session.use_only_cookies', 1);
    // Session ID can only be generated from my own server.
    ini_set('session.use_strict_mode', 1);
    // Prevents Cross-Site-Scripting (XSS)
    ini_set('session.cookie_httponly', 1);
    // Set the life-time of a session.
    ini_set('session.gc_maxlifetime', $p_sessionLifetime+60);
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 100);

    self::setCacheExpire((((int) $p_sessionLifetime+60)/60));

    // Set parameters for the session-cookie.
    self::setCookieParms([
      'lifetime' => $p_sessionLifetime,
      'domain' => SITE_DOMAIN_NAME,
      'path' => '/',
      'secure' => false,
      'httponly' => true,
      'samesite' => 'Strict'
    ]);
  }

  /**
   * Starts or resume an existing session and continues to renew the session-id when it expires.
   * @param int $p_sessionLifetime
   */
  public function startWithRegeneration(int $p_sessionLifetime =self::SESS_LIFETIME_DEFAULT) : void {
    // Start new or resume existing session
    @session_start();
    if (!isset($_SESSION['last_regeneration'])) {
      session_regenerate_id(true);
      $_SESSION['last_regeneration'] = time();
    } else {
      // Lifetime in seconds
      $regenerateInterval = $p_sessionLifetime;
      $currentTimestamp = time();
      if ($currentTimestamp - $_SESSION['last_regeneration'] >= $regenerateInterval) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
      }
    }
  }

  /**
   * Sends a session-cookie.
   * @param string $p_sessId Session-id to use in the session-cookie.
   * @param string $p_sessName
   */
  public function sendSessionCookie(string $p_sessId, string $p_sessName ='PHPSESSID') : void {
    $this->setCookieParms($this->session_options);
    $cookieExpireDate = CustomDateTime::getCookieExpireDate($this->getSessionLifetime());
    setcookie($p_sessName, $p_sessId, $cookieExpireDate, '/', SITE_DOMAIN_NAME);
    // setcookie($p_sessName, $p_sessId, ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'), ini_get('session.cookie_secure'), ini_get('session.cookie_httponly'));
  }

  /**
   * Set session cache-expire time.
   * @access public
   * @param int $p_intTime Expire-time in minutes.
   * @return void
   */
  public static function setCacheExpire(int $p_intTime =180) : void {
    session_cache_expire($p_intTime);
  }

  /**
   * getCacheExpire - Return session expire-time.
   * @access public
   * @return int session expire timer.
   */
  public function getCacheExpire() : int {
    return session_cache_expire();
  }

  /**
   * Free all session variables
   * @access public
   */
  public function unsetVars() : void {
    session_unset();
  }

  /**
   * Set session variables
   *
   * @access public
   * @param string $p_key
   * @return void
   */
  public function set(string $p_key, $p_value) : void {
    $_SESSION[$p_key] = $p_value;
  }

  /**
   * Return session variable
   *
   * @access public
   * @param string $p_key
   * @return mixed
   */
  public function get(string $p_key) {
    if (isset($_SESSION[$p_key])) {
      return $_SESSION[$p_key];
    } else {
      return null;
    }
  }
}