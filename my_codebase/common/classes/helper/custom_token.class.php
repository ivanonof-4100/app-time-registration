<?php
namespace Common\Classes\Helper;

/**
 * Filename     : custom_token.class.php
 * Language     : PHP v7.4
 * Date created : 06/05-2023, Ivan
 * Last modified: 13/05-2023, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2023 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * A token is in short a string that enables us to build a better security where we only accepts
 * user-input, when and if the security-token is exact the same as we set it on each server-respond.
 * 
 * This class will be used to for both generating and verifying dynamic security-tokens.
 * Both API-keys and other tokens like security-token used in a form.
 */
class CustomToken {
    const TOKEN_BYTE_LENGTH =12;

    /**
     * @var string
     */
    protected $token;

    /**
     * Default constructor
     * @param string $p_token Default blank.
     */
    public function __construct(string $p_token ='') {
        if (!empty($p_token)) {
          $this->setToken($p_token);  
        } else {
          $dynamicToken = $this->generateToken(self::TOKEN_BYTE_LENGTH);
          $this->setToken($dynamicToken);
        }
    }

    public function __destruct() {
    }

    /**
     * @return string
     */
    public function __toString() : string {
        return sprintf("XSS-Token: %s", $this->getToken());
    }

    /**
     * @param string $p_token Default blank.
     * @return void
     */
    public function setToken(string $p_token ='') : void {
        $this->token = $p_token;
    }

    /**
     * @return string
     */
    public function getToken() : string {
        return $this->token;
    }

    /**
     * @param string $p_token Default blank.
     * @return CustomToken
     */
    public static function getInstance(string $p_token ='') : CustomToken {
        return new CustomToken($p_token);
    }

    // Service methods

    /**
     * Generate a dynamic security-token.
     * @param int $p_tokenByteLength Default 12 which will generate tokens 24-chars long.
     * @return string
     */
    public function generateToken(int $p_tokenByteLenght =self::TOKEN_BYTE_LENGTH) : string {
        return bin2hex(random_bytes($p_tokenByteLenght));
    }

    /**
     * @param string $p_userToken
     * @return bool
     */
    public function validateToken(string $p_userToken) : bool {
       return hash_equals($this->getToken(), $this->base64DecodeToken($p_userToken));
    }

    /**
     * Base64-decode the given string.
     * @param string $p_encodedToken
     * @return string
     */
    protected function base64DecodeToken(string $p_encodedToken) : string {
        return base64_decode($p_encodedToken);
    }
}