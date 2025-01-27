<?php
namespace Common\Classes\Session;

class SessionManager 
{
    public function __construct() {        
    }

    public function __destruct() {
    }

    public function getInstance() : SessionManager {
        return new SessionManager();
    }

    public function sendSessionCookie() {
        
    }
}