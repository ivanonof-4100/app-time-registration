<?php
namespace App\Modules\Timesheets\Classes\Renderes;

use Common\Classes\Renderes\StdRenderer;
use Common\Classes\Renderes\Template;
use Common\Classes\LanguagefileHandler;
use Common\Classes\Datetime\CustomDateTime;
use Common\Classes\Db\DBAbstraction;
use App\Modules\Timesheets\Classes\Model\Timesheet;
use DateTime;

class TimesheetRenderer extends StdRenderer {

    public function __construct(LanguagefileHandler $p_languagefileHandler, bool $p_isPrintPage =FALSE) {
        parent::__construct($p_languagefileHandler, $p_isPrintPage);
    }

    public function __destruct() {
        parent::__destruct();
    }

    public static function getInstance(LanguagefileHandler $p_languagefileHandler, bool $p_isPrintPage =FALSE) : TimesheetRenderer {
        $timesheetRenderer = new TimesheetRenderer($p_languagefileHandler, $p_isPrintPage);
        $timesheetRenderer->startOutputBuffering();
        return $timesheetRenderer;
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

        // Set year start and end-dates.
        $yearCustomDateTime = CustomDateTime::getInstance();

        $yearStartDate = new DateTime();
        $yearStartDate->setDate($yearCustomDateTime->getYearNumber(), 1, 1);
        $yearEndDate = new DateTime();
        $yearEndDate->setDate($yearCustomDateTime->getYearNumber(), 12, 31);
        
//        echo sprintf("%s - %s", $yearStartDate->format(CustomDateTime::getISODateFormat()), $yearEndDate->format(CustomDateTime::getISODateFormat()));

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
          $arrAccumulatedHours_week[] = ['total_hours_regular' => (float) 0, 'total_hours_overtime' => (float) 0, 'total_hours_break' => (float) 0];
        } else {
          // Retrive the accumulated hours for the period from the database.
          $arrAccumulatedHours_week = Timesheet::retriveAccumulatedHours($p_dbAbstraction,
                                                                    $p_employeeUUID,
                                                                    $datetimeWeekDateStart->format(CustomDateTime::getISODateFormat()),
                                                                    $datetimeWeekDateEnd->format(CustomDateTime::getISODateFormat()));
        }

        // Retrive the accumulated annualy hours
        // echo sprintf("%s - %s", $yearStartDate->format(CustomDateTime::getISODateFormat()), $yearEndDate->format(CustomDateTime::getISODateFormat()));
        $arrAccumulatedHours_annualy = Timesheet::retriveAccumulatedHours($p_dbAbstraction,
                                                                          $p_employeeUUID,
                                                                          /* '2022-01-01' */ $yearStartDate->format(CustomDateTime::getISODateFormat()),
                                                                          /* '2022-12-31' */ $yearEndDate->format(CustomDateTime::getISODateFormat())
                                                                         );
        if (empty($arrAccumulatedHours_annualy)) {
          $arrAccumulatedHours_annualy[] = array('total_hours_regular' => 0.0,
                                               'total_hours_overtime' => 0.0,
                                               'total_hours_break' => 0.0);
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
        $template->assign('arrAccumulatedHours_week', $arrAccumulatedHours_week[0]);
        $template->assign('totalHours_week', array_sum($arrAccumulatedHours_week[0]));
        $template->assign('arrAccumulatedHours_annualy', $arrAccumulatedHours_annualy[0]);
        $template->assign('totalHours_annualy', array_sum($arrAccumulatedHours_annualy[0]));
        $template->assign('arrOptions_weeks', $arrOptions_weeks);

        // We also need to pass employee_uuid
        $template->assign('employeeUuid', $p_employeeUUID); // TEST employee: Dr. John Doe ;-)
        // Fetch resulting output.
        $timesheetOuput = $template->fetch();

        // Display
        $pageMetaDescription = 'Weekly Timesheet administration';
        $pageMetaKeywords = 'Timesheets, Weekly, Your used time, Administation';
        $this->displayAsPage($pageTitle, $pageMetaDescription, $pageMetaKeywords, $timesheetOuput);
    }
}