<?php
namespace App\Modules\Timesheets\Classes\Controller;

use Exception;
use Common\Classes\StdApp;
use Common\Classes\Controller\StdController;
use Common\Classes\Controller\StdControllerInterface;
use Common\Classes\Helper\CustomToken;
use Common\Classes\ResponseCode;
use Common\Classes\Url;
use Common\Classes\InputHandler;
use Common\Classes\LanguagefileHandler;
use Common\Classes\Helper\FlashMessage;
use App\Modules\Timesheets\Classes\Renderes\TimesheetRenderer;
use App\Modules\Timesheets\Classes\Model\Timesheet;
use Common\Classes\Datetime\CustomDateTime;

/**
 * Filename     : timesheet_controller.class.php
 * Language     : PHP v7.4+
 * Date created : Ivan, 02/10-2022
 * Last modified: Ivan, 24/06-2024
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright: Copyright (C) 2024 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * This class handles the flow-control and all the coordination in the proces of handling everything about timesheets.
 * 
 * TODO:
 * Add use of optimstic Concurrency handling to prevent a LOST-UPDATE situation.
 * https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/If-Unmodified-Since
 */
class TimesheetController extends StdController implements StdControllerInterface {
    // Attributes
    /**
     * @var TimesheetRenderer
     */
    protected $rendererInstance;

    /**
     * Constructor
     */
    public function __construct(string $p_lang =APP_LANGUAGE_IDENT, string $p_charset ='utf8', StdApp $p_appInstance) {
      parent::__construct($p_appInstance);
      $this->rendererInstance = TimesheetRenderer::getInstance(LanguagefileHandler::getInstance(FALSE, $p_lang, $p_charset));
    }

    public function __destruct() {
      parent::__destruct();        
    }

    /**
     * @param string $p_lang
     * @param string $p_charset Default 'utf8'
     * @param StdApp $p_appInstance
     * @return TimesheetController
     */
    public static function getInstance(string $p_lang ='da', string $p_charset ='utf8', StdApp $p_appInstance) : TimesheetController {
      return new TimesheetController($p_lang, $p_charset, $p_appInstance);
    }

    /**
     * @return TimesheetRenderer
     */
    public function getInstance_renderer() : TimesheetRenderer {
      return $this->rendererInstance;
    }

    /**
     * Initialize dependencies and the registry of the web-app.
     * @return void
     */
    public function initalizeDependencies() : void {
      // Start output-buffering.
      $rendererInstance = $this->getInstance_renderer();
      $rendererInstance->startOutputBuffering();

      $arrSettings = $this->getLoadedSettings();
      if (array_key_exists('app_lang_supported', $arrSettings)) {
        // Set supported languages
        $instanceRenderer = $this->getInstance_renderer();
        $instanceRenderer->setAttr_arrLangs($arrSettings['app_lang_supported']);
      }

      try {
        // Connect to database and start session-handling.
        $this->initDependencies($arrSettings);
      } catch (Exception $e) {
        // Handled errors.
        $rendererInstance = $this->getInstance_renderer();
        $rendererInstance->renderHandledAlert($e->getMessage());
        exit(0);
      }
    }

    /**
     * This method handles input from consulents with their weekly-timesheets.
     * @return void
     */
    public function handleWeeklyTimesheets() : void {
      $this->initalizeDependencies();
      $sessionHandler = $this->getInstance_sessionHandler();
      if (!$sessionHandler) {
        /* An empty security-token will make it regenerate the dynamic security-token and then
           the new page will have the new-token which is base64-encoded in the front-end only.
        */
        $knownSecurityToken ='';
      } else {
        // Default to the date of today
        $focusDateTime = CustomDateTime::getInstance();
        $focusDay = $focusDateTime->getFormatedISODate();
        // Hard-coded employee_uuid in the TEST-app right now.
        $sessionHandler->set('employee_uuid', '597e8483-467d-11ed-b005-1c1bb5a9bf9b');
        $sessionHandler->set('focus_workday', $focusDay);

        // $sessionHandler->set('flash_message_timesheet', 'TEST mesg: I am testing to see if things work!');
        $knownSecurityToken = $sessionHandler->get('security_token');
      }

      // Use what we have.
      $customToken = CustomToken::getInstance($knownSecurityToken);

      /**
       * _token represents the posted CSRF-token
       * $knownSecurityToken is the CSRF-token registered in the session.
       */
      $inputHandler = InputHandler::getInstance();
      $url = Url::getInstance();
      if ($url->isRequestMethod_POST()) {
        $arrInputParam_token = $inputHandler->retriveInputParameter('_token', InputHandler::ACCEPTED_DATATYPE_STR, InputHandler::INPUT_SOURCE_POST);
        if ($arrInputParam_token['is_set'] && $arrInputParam_token['is_valid']) {
          if (empty($knownSecurityToken)) {
            // Handled errors.
            $rendererInstance = $this->getInstance_renderer();
            $rendererInstance->renderHandledAlert('Dynamic Security Token was NOT found in SESSION-variable ...');

            $this->redirectBrowser($url->getRequestURI(), ResponseCode::HTTP_PAGE_EXPIRED);

            // Throw new Exception('Dynamic Security Token was NOT found in SESSION-variable ...');
            // Display custom error-page
            // Loose posted vars by redirecting
            exit(1);
          }

          try {
            // Check if the post-request have the correct security-token.
            if ($customToken->validateToken($arrInputParam_token['value'])) {
              // Proceed with Server-side input-validation of user-input.
              $arrInputParam_timesheetUUID = $inputHandler->retriveInputParameter('timesheet_uuid', InputHandler::ACCEPTED_DATATYPE_UUID, InputHandler::INPUT_SOURCE_POST);
              $arrInputParam_employeeUUID = $inputHandler->retriveInputParameter('employee_uuid', InputHandler::ACCEPTED_DATATYPE_UUID, InputHandler::INPUT_SOURCE_POST);
              $arrInputParam_workDate = $inputHandler->retriveInputParameter('timesheet_work_date', InputHandler::ACCEPTED_DATATYPE_STR, InputHandler::INPUT_SOURCE_POST);
              $arrInputParam_hoursRegular = $inputHandler->retriveInputParameter('timesheet_hours_regular', InputHandler::ACCEPTED_DATATYPE_FLOAT, InputHandler::INPUT_SOURCE_POST);
              $arrInputParam_hoursOvertime = $inputHandler->retriveInputParameter('timesheet_hours_overtime', InputHandler::ACCEPTED_DATATYPE_FLOAT, InputHandler::INPUT_SOURCE_POST);
              $arrInputParam_hoursBreak = $inputHandler->retriveInputParameter('timesheet_hours_break', InputHandler::ACCEPTED_DATATYPE_FLOAT, InputHandler::INPUT_SOURCE_POST);

              // Get the active database-connection from the codebase-registry.
              $codebaseRegistry = $this->getInstance_codebaseRegistry();
              $dbAbstractionInstance = $codebaseRegistry->getInstance_dbConnection();

              // Set the appropriate attributes on the model-class.
              if ($arrInputParam_timesheetUUID['is_set'] && $arrInputParam_timesheetUUID['is_valid']) {
                $timesheet = Timesheet::getInstance_byObjUuid($dbAbstractionInstance, $arrInputParam_timesheetUUID['value']);
              } else {
                $timesheet = Timesheet::getInstance('', $arrInputParam_employeeUUID['value'], $arrInputParam_workDate['value']);
              }

              // @TODO: Add check that will prevent a lost-update situation before over-writing existing data.
              // Check if the instance have been updated after the datetime-stamp that we go when we first retrived the record.

              if ($arrInputParam_hoursRegular['is_set'] && $arrInputParam_hoursRegular['is_valid']) {
                $timesheet->setAttr_timesheet_hours_regular($arrInputParam_hoursRegular['value']);
              }

              if ($arrInputParam_hoursOvertime['is_set'] && $arrInputParam_hoursOvertime['is_valid']) {
                $timesheet->setAttr_timesheet_hours_overtime($arrInputParam_hoursOvertime['value']);
              }

              if ($arrInputParam_hoursBreak['is_set'] && $arrInputParam_hoursBreak['is_valid']) {
                $timesheet->setAttr_timesheet_hours_break($arrInputParam_hoursBreak['value']);
              }

              // Lets assume that, if the model-class have a valid-UUID then we also have a record in the database.
              $timesheetUUID = $timesheet->getAttr_timesheet_uuid();
              if (!empty($timesheetUUID) && InputHandler::isValidUUID($timesheetUUID)) {
                $timesheet->markAsChanged();
              } else {
                $timesheet->markAsInserted();
              }

              // Persist the new data
              try {
                $wasSuccesful = $timesheet->save($dbAbstractionInstance, TRUE);
                $sessionHandler->set('focus_workday', $arrInputParam_workDate['value']);
                $sessionHandler->set('flash_message_timesheet', 'TEST 2: I am testing to see if things work!');
/*
                if ($wasSuccesful && isset($sessionHandler)) {
                // FlashMessage::getInstance('From controller: Your data was saved successfully ...', FlashMessage::MESSAGE_TYPE_SUCCESS)

                // if ($wasSuccesful) {
                  $sessionHandler->set('focus_workday', $arrInputParam_workDate['value']);
                  $sessionHandler->set('flash_message_timesheet', 'TEST 2: I am testing to see if things work!');
                }
*/
/*
// Debug
$timesheetRenderer = $this->getInstance_renderer();
$timesheetRenderer->stopOutputBuffering();

echo PHP_EOL .'<pre>'. __METHOD__ .': arrInputParam_workDate =';
var_dump($arrInputParam_workDate);
echo '</pre>';

echo PHP_EOL .'<pre>'. __METHOD__ .': _SESSION =';
var_dump($_SESSION);
echo '</pre>';
exit(0);
*/
                // Redirect back to the original URI to loose the posted variables again.
                StdController::redirectBrowser($url->getRequestURI(), ResponseCode::HTTP_SEE_OTHER);
                exit(0);
              } catch(Exception $e) {
                $rendererInstance = $this->getInstance_renderer();
                $rendererInstance->renderHandledAlert($e->getMessage());
                exit(5);
              }
            }
          } catch (Exception $e) {
            $rendererInstance = $this->getInstance_renderer();
            $rendererInstance->renderHandledAlert($e->getMessage());
            exit(1);
          }
        }
      } else {
        // NOT a POST-request => Get the active database-connection from the codebase-registry.
        $codebaseRegistry = $this->getInstance_codebaseRegistry();
        $dbAbstractionInstance = $codebaseRegistry->getInstance_dbConnection();
        // Get the employee_uuid of the current user from the session-data.
        $employeeUUID = $sessionHandler->get('employee_uuid');

        // TODO: I also need to check if the focus-day is within the week, but this will do for now.

        // Set which day in the week-accordion would get the focus.
        if (isset($_SESSION['focus_workday'])) {
          $focusDay = $sessionHandler->get('focus_workday');
        } else {
          // Default to the date of today
          $focusDateTime = CustomDateTime::getInstance();
          $focusDay = $focusDateTime->getFormatedISODate();
        }

/* Debug
$timesheetRenderer = $this->getInstance_renderer();
$timesheetRenderer->stopOutputBuffering();

echo '<pre>'. __METHOD__ .': TEST focusDay =';
var_dump($focusDay);
echo '</pre>';
echo '<pre>'. __METHOD__ .': TEST _SESSION =';
var_dump($_SESSION);
echo '</pre>';
exit(0);
*/
        // Display the form for inputing current data that we have for the logged-in user.
        $arrInputParam_week = $inputHandler->retriveInputParameter('week', InputHandler::ACCEPTED_DATATYPE_POS_INT, InputHandler::INPUT_SOURCE_GET);
        if ($arrInputParam_week['is_set'] && $arrInputParam_week['is_valid']) {
          // Display data for custom selected week.
          $timesheetRenderer = $this->getInstance_renderer();
          $timesheetRenderer->renderWeeklyTimesheet($dbAbstractionInstance,
                                                    $employeeUUID,
                                                    $focusDay,
                                                    $arrInputParam_week['value']);
        } else {
          // Display data for the current week.
          $timesheetRenderer = $this->getInstance_renderer();
          $timesheetRenderer->renderWeeklyTimesheet($dbAbstractionInstance, $employeeUUID, $focusDay);
/*
echo PHP_EOL .'<pre>'. __METHOD__ .': _SESSION =';
var_dump($_SESSION);
echo '</pre>';
exit(0);
*/
        }
      }
    }
} // End class