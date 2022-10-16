<?php
require './../../../bootstrap.php';

use Common\Classes\StdApp;
use App\Modules\Timesheets\Classes\Controller\TimesheetController;

$stdApp = StdApp::getInstance();
// $stdApp->run();
$timesheetController = TimesheetController::getInstance($stdApp, APP_LANGUAGE_IDENT);
$timesheetController->handleWeeklyTimesheets();