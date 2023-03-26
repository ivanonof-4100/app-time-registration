<?php
require_once './../../../bootstrap.php';

use Common\Classes\StdApp;
use App\Modules\Timesheets\Classes\Controller\TimesheetController;

$stdApp = StdApp::getInstance();
// $stdApp->run();
$timesheetController = TimesheetController::getInstance(APP_LANGUAGE_IDENT, APP_DEFAULT_CHARSET, $stdApp);
$timesheetController->handleWeeklyTimesheets();