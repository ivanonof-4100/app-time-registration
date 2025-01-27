<?php
require_once '../../bootstrap.php';
use Common\Classes\StdApp;
use Common\Classes\Controller\ErrorController;

$app = StdApp::getInstance();
$errorController = ErrorController::getInstance('en', 'utf8', $app);
$errorController->displayError_page404();