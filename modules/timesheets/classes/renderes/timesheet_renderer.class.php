<?php
namespace App\Modules\Timesheets\Classes\Renderes;

use Common\Classes\Renderes\StdRenderer;
use Common\Classes\Renderes\Template;
use Common\Classes\LanguagefileHandler;
use Common\Classes\Datetime\CustomDateTime;
use Common\Classes\Db\DBAbstraction;
use App\Modules\Timesheets\Classes\Model\Timesheet;

class TimesheetRenderer extends StdRenderer {

    public function __construct(LanguagefileHandler $p_languagefileHandler, bool $p_isPrintPage =FALSE) {
        parent::__construct($p_languagefileHandler, $p_isPrintPage);
    }

    public function __destruct() {
        parent::__destruct();
    }

    public static function getInstance(LanguagefileHandler $p_languagefileHandler, bool $p_isPrintPage =FALSE) : TimesheetRenderer {
        return new TimesheetRenderer($p_languagefileHandler, $p_isPrintPage);
    }

    /**
     * Generate options-array to be able to select a specific week in the front-end.
     * @param LanguagefileHandler
     * @return array
     */
    public function getOptions_weeks(LanguagefileHandler $p_languagefileHandler) : array {
      $customDateTime = CustomDateTime::getInstance();
      $curWeekNumber = $customDateTime->getWeekNumber();

      $arrOptions_weeks = array();
      for ($week =1; ($week <= $curWeekNumber); $week++) {
        $arrOptions_weeks["$week"] = $p_languagefileHandler->getEntryContent('CUSTOM_DATETIME_WEEK_SHORT', $week);
      }
      return $arrOptions_weeks;
    }

    /**
     * @param DBAbstraction
     * @param string $p_employeeUUID
     * @param int $p_weekNumber Default zero.
     * 
     * @return void
     */
    public function renderWeeklyTimesheet(DBAbstraction $p_dbAbstraction, string $p_employeeUUID, int $p_weekNumber =0) : void {
        // Load language-file
        $languagefileHandler = $this->getInstance_languageFileHandler();
        $languagefileHandler->loadLanguageFile('custom_datetime');

        if (($p_weekNumber != 0) && ($p_weekNumber >=1 && $p_weekNumber <=53)) {
          // @TODO validate that its valid for the year.
          $customDateTime = CustomDateTime::getInstance();
          $weekNumber = $p_weekNumber;
          $yearNumber = $customDateTime->getYearNumber();
          $customDateTime->setDate_toWeekStart($yearNumber, $weekNumber);
        } else {
          // Default: Render current week of the current year.
          $customDateTime = CustomDateTime::getInstance();
          $weekNumber = $customDateTime->getWeekNumber();
          $yearNumber = $customDateTime->getYearNumber();
          $customDateTime->setDate_toWeekStart($yearNumber, $weekNumber);
        }

        // Get the period start and end dates.
        $datetimeWeekDateStart = $customDateTime->getInstance_dateTime();
        $customDateTime->setDate_toWeekEnd($yearNumber, $weekNumber);
        $datetimeWeekDateEnd = $customDateTime->getInstance_dateTime();

        // Generate options-array to be able to select a specific week in the front-end.
        $arrOptions_weeks = $this->getOptions_weeks($languagefileHandler);

        // Get all existing data for the period using a single query.
        $arrRegisteredData = Timesheet::retriveRegisteredData_asAssocArray($p_dbAbstraction,
                                                                      $p_employeeUUID,
                                                                      $datetimeWeekDateStart->format(CustomDateTime::getISODateFormat()),
                                                                      $datetimeWeekDateEnd->format(CustomDateTime::getISODateFormat()));
        if (empty($arrRegisteredData)) {
          // There are not any data for the period yet - set default.
          $arrAccumulatedHours[] = ['total_hours_regular' => (float) 0, 'total_hours_overtime' => (float) 0, 'total_hours_break' => (float) 0];
        } else {
          // Retrive the accumulated hours for the period from the database.
          $arrAccumulatedHours = Timesheet::retriveAccumulatedHours($p_dbAbstraction,
                                                                    $p_employeeUUID,
                                                                    $datetimeWeekDateStart->format(CustomDateTime::getISODateFormat()),
                                                                    $datetimeWeekDateEnd->format(CustomDateTime::getISODateFormat()));
        }

        // Week-days
        $datetimeWorkDay = clone $datetimeWeekDateStart;
        for ($daySeq =1; $daySeq <=7; $daySeq++) {
          $workDate = $datetimeWorkDay->format('d-m-Y');
          $isoWorkDate = $datetimeWorkDay->format(CustomDateTime::getISODateFormat());

          // Set localized content.
          $curLanguageEntry = sprintf('CUSTOM_DATETIME_WEEKDAY_%d', $daySeq);
          if ($languagefileHandler->doesLanguageEntryExists($curLanguageEntry)) {
            // Check if there are any registration on date of isoWorkDate for the employee
            if (array_key_exists($isoWorkDate, $arrRegisteredData)) {
              $arrWeekdays[$daySeq] = ['weekday_name' => $languagefileHandler->getEntryContent($curLanguageEntry),
                                       'work_date' => $workDate,
                                       'iso_work_date' => $isoWorkDate,
                                       'timesheet_uuid' => $arrRegisteredData[$isoWorkDate]["timesheet_uuid"],
                                       'hours_regular' => $arrRegisteredData[$isoWorkDate]['timesheet_hours_regular'],
                                       'hours_overtime' => $arrRegisteredData[$isoWorkDate]['timesheet_hours_overtime'],
                                       'hours_break' => $arrRegisteredData[$isoWorkDate]['timesheet_hours_break']
                                      ];
            } else {
              $arrWeekdays[$daySeq] = ['weekday_name' => $languagefileHandler->getEntryContent($curLanguageEntry),
                                       'work_date' => $workDate,
                                       'iso_work_date' => $isoWorkDate,
                                       'timesheet_uuid' => '',
                                       'hours_regular' => 0,
                                       'hours_overtime' => 0,
                                       'hours_break' => 0
                                      ];
            }
          }

          $datetimeWorkDay->modify('+1 day');
        }

        $pageTitle = 'Weekly Timesheets';
        $template = Template::getInstance('timesheet_register_weekly.tpl');

        // Send the variables to the template.
        $template->assign('yearNumber', $yearNumber);
        $template->assign('weekNumber', $weekNumber);
        $template->assign('weekDateStart', $datetimeWeekDateStart->format('d-m-Y'));
        $template->assign('weekDateEnd', $datetimeWeekDateEnd->format('d-m-Y'));
        $template->assign('arrWeekdays', $arrWeekdays);
        $template->assign('arrAccumulatedHours', $arrAccumulatedHours[0]);
        $template->assign('totalHours', array_sum($arrAccumulatedHours[0]));
        $template->assign('arrOptions_weeks', $arrOptions_weeks);

        // We also need to pass employee_uuid
        $template->assign('employeeUuid', $p_employeeUUID); // TEST employee: Dr. John Doe ;-)
        // Fetch resulting output.
        $timesheetOuput = $template->fetch();

        // Display
        $this->displayAsPage($pageTitle, $timesheetOuput);
    }
}