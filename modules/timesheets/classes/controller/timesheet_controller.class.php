<?php
namespace App\Modules\Timesheets\Classes\Controller;

use Exception;
use Common\Classes\StdApp;
use Common\Classes\Controller\StdController;
use App\Modules\Timesheets\Classes\Renderes\TimesheetRenderer;
use App\Modules\Timesheets\Classes\Model\Timesheet;
use Common\Classes\InputHandler;

/**
 * Script-name  : timesheet_controller.class.php
 * Language     : PHP v7.4+
 * Date created : IMA, 02/10-2022
 * Last modified: IMA, 07/10-2022
 * Developers   : @author IMA, Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright: Copyright (C) 2022 by Ivan Mark Andersen
 *
 * Description:
 *  This class handles the flow-control and all the coordination in the proces of handling everything about timesheets.
 */
class TimesheetController extends StdController {
    // Attributes
    /**
     * @var TimesheetRenderer
     */
    protected $rendererInstance;

    /**
     * Constructor
     */
    public function __construct(StdApp $p_appInstance, string $p_lang ='da', string $p_charset ='utf8') {
      parent::__construct($p_lang, $p_charset);
      // Init StdApp instance in the app-registry
      $this->setAppInstance($p_appInstance);

      $arrInputParam_print = $this->retriveInputParameter('print', 'boolean', '_GET');
      if ($arrInputParam_print['is_set'] && $arrInputParam_print['is_valid']) {
        $this->rendererInstance = TimesheetRenderer::getInstance($this->getInstance_languageFileHandler(), $arrInputParam_print['value']);
      } else {
        $this->rendererInstance = TimesheetRenderer::getInstance($this->getInstance_languageFileHandler());
      }
    }

    public function __destruct() {
        parent::__destruct();        
    }

    public function getInstance_renderer() : TimesheetRenderer {
        return $this->rendererInstance;
    }

    /**
     * @return TimesheetController
     */
    public static function getInstance($p_appInstance) : TimesheetController {
        return new TimesheetController($p_appInstance);
    }

    /**
     * This method handles input from consulents with their weekly-timesheets.
     * @return void
     */
    public function handleWeeklyTimesheets() : void {
        if (self::isRequestMethod_POST()) {
          $arrInputParam_token = $this->retriveInputParameter('_token', 'string', '_POST');
          if ($arrInputParam_token['is_set'] && $arrInputParam_token['is_valid']) {
            try {
              // First lets check if the post-request have used the correct token.
              if (self::isCorrectSecurityToken($arrInputParam_token['value'])) {
                // Okay, Lets proceed with Server-side validation of user-input.
                $arrInputParam_timesheetUUID = $this->retriveInputParameter('timesheet_uuid', 'uuid', '_POST');
                $arrInputParam_employeeUUID = $this->retriveInputParameter('employee_uuid', 'uuid', '_POST');
                $arrInputParam_workDate = $this->retriveInputParameter('timesheet_work_date', 'string', '_POST');
                $arrInputParam_hoursRegular = $this->retriveInputParameter('timesheet_hours_regular', 'float', '_POST');
                $arrInputParam_hoursOvertime = $this->retriveInputParameter('timesheet_hours_overtime', 'float', '_POST');
                $arrInputParam_hoursBreak = $this->retriveInputParameter('timesheet_hours_break', 'float', '_POST');

                // Get the active database-connection from the codebase-registry.
                $codebaseRegistry = $this->getInstance_codebaseRegistry();
                $dbAbstractionInstance = $codebaseRegistry->getInstance_dbConnection();

                // Set the appropiate attributes on the data model-class
                if ($arrInputParam_timesheetUUID['is_set'] && $arrInputParam_timesheetUUID['is_valid']) {
                  // Lookup if the employee has a timesheet for that given workday, if not its new else its changed.
                  $timesheet = Timesheet::getInstance_byObjUuid($dbAbstractionInstance, $arrInputParam_timesheetUUID['value']);
                } else {
                  $timesheet = Timesheet::getInstance('', $arrInputParam_employeeUUID['value'], $arrInputParam_workDate['value']);
                }

                if ($arrInputParam_hoursRegular['is_set'] && $arrInputParam_hoursRegular['is_valid']) {
                  $timesheet->setAttr_timesheet_hours_regular($arrInputParam_hoursRegular['value']);
                }

                if ($arrInputParam_hoursOvertime['is_set'] && $arrInputParam_hoursOvertime['is_valid']) {
                  $timesheet->setAttr_timesheet_hours_overtime($arrInputParam_hoursOvertime['value']);
                }

                if ($arrInputParam_hoursBreak['is_set'] && $arrInputParam_hoursBreak['is_valid']) {
                  $timesheet->setAttr_timesheet_hours_break($arrInputParam_hoursBreak['value']);
                }

                // Lets assume that, if the model-class have a valid-UUID then we also have a record in the datebase.
                $objUUID = $timesheet->getAttr_timesheet_uuid();
                if (!empty($objUUID) && InputHandler::isValidUUID($objUUID)) {
                  $timesheet->markAsChanged();
                } else {
                  $timesheet->markAsInserted();
                }

                // Persist the new data
                try {
                  $wasSuccesful = $timesheet->save($dbAbstractionInstance, TRUE);
                } catch(Exception $e) {
                  echo $e->getMessage();
                  exit(5);
                }

                // Redirect back to the original URI to loose the posted variables again.
                StdController::redirectBrowser($_SERVER["REQUEST_URI"]);
                exit(0);
              }
            } catch (Exception $e) {
              // Not the correct token used!
              echo $e->getMessage();
              exit(7);
            }
          }
        } else {
          // Its NOT a POST-request

          // Get the active database-connection from the codebase-registry.
          $codebaseRegistry = $this->getInstance_codebaseRegistry();
          $dbAbstractionInstance = $codebaseRegistry->getInstance_dbConnection();

          // TEST: Hard-coded because it only a TEST-app right now and we dont have login.
          $employeeUUID = '597e8483-467d-11ed-b005-1c1bb5a9bf9b';

          $arrInputParam_week = $this->retriveInputParameter('week', 'pos_int', '_GET');
          if ($arrInputParam_week['is_set'] && $arrInputParam_week['is_valid']) {
            // Display the form for inputting current data that we have for the logged-in user.
            $timesheetRenderer = $this->getInstance_renderer();
            $timesheetRenderer->renderWeeklyTimesheet($dbAbstractionInstance,
                                                      $employeeUUID,
                                                      $arrInputParam_week['value']);
          } else {
            // Display the form for inputing data for the logged-in user.
            $timesheetRenderer = $this->getInstance_renderer();
            $timesheetRenderer->renderWeeklyTimesheet($dbAbstractionInstance,
                                                      $employeeUUID);
          }
        }
    }
} // End class