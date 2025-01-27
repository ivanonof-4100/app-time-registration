<?php
namespace App\Modules\Timesheets\Classes\Renderes;

use DateTime;
use Exception;
// NOTE: NumberFormatter needs the PHP-module php7.4-intl
use NumberFormatter;
use Common\Classes\Renderes\Template;
use Common\Classes\Renderes\StdRenderer;
use Common\Classes\Renderes\MenuRenderer;
use Common\Classes\LanguagefileHandler;
use Common\Classes\Datetime\CustomDateTime;
use Common\Classes\Db\DBAbstraction;
use App\Modules\Timesheets\Classes\Model\Timesheet;
// use Common\Classes\Helper\FlashMessage;

class TimesheetRenderer extends StdRenderer {
    protected $custom_template_path;

    public function __construct(LanguagefileHandler $p_languagefileHandler) {
      parent::__construct($p_languagefileHandler);
    }

    public function __destruct() {
      parent::__destruct();
    }

    /**
     * @param LanguagefileHandler $p_languagefileHandler
     */
    public static function getInstance(LanguagefileHandler $p_languagefileHandler) : TimesheetRenderer {
      return new TimesheetRenderer($p_languagefileHandler);
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
      for ($week =$curWeekNumber; ($week >= 1); $week--) {
        $arrOptions_weeks["$week"] = $p_languagefileHandler->getEntryContent('CUSTOM_DATETIME_WEEK_SHORT', $week);
      }
      return $arrOptions_weeks;
    }

    /**
     * @param DBAbstraction $p_dbAbstraction
     * @param string $p_employeeUUID
     * @param string $p_focusDay eg.: '2024-11-05'
     * @param int $p_weekNumber Default zero.
     * 
     * @return void
     */
    public function renderWeeklyTimesheet(DBAbstraction $p_dbAbstraction, string $p_employeeUUID, string $p_focusDay, int $p_weekNumber =0) : void {
        // Load language-file
        $languagefileHandler = $this->getInstance_languageFileHandler();
        $languagefileHandler->loadLanguageFile('custom_datetime', APP_LANGUAGE_PATH);

        $arrLocalizedMonths = CustomDateTime::getLocalizedMonths($languagefileHandler);
        $localizedDateFormat = CustomDateTime::getLocalizedDateFormat($languagefileHandler);

        $menuRenderer = MenuRenderer::getInstance($languagefileHandler);
        $this->setMainNavigation($menuRenderer->render_mainMenu($p_dbAbstraction, $languagefileHandler->getLanguageIdent(), $this->getAttr_arrLangs()));

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

        // Retrive the annualy accumulated-hours.
        $arrAccumulatedHours_annualy = Timesheet::retriveAccumulatedHours($p_dbAbstraction,
                                                                          $p_employeeUUID,
                                                                          $yearStartDate->format(CustomDateTime::getISODateFormat()),
                                                                          $yearEndDate->format(CustomDateTime::getISODateFormat())
                                                                         );
        if (empty($arrAccumulatedHours_annualy)) {
          $arrAccumulatedHours_annualy[] = array('total_hours_regular' => 0.0,
                                               'total_hours_overtime' => 0.0,
                                               'total_hours_break' => 0.0);
        }

        // Week-days
        $datetimeWorkDay = clone $datetimeWeekDateStart;
        for ($daySeq =1; $daySeq <=7; $daySeq++) {
          $workDate = $datetimeWorkDay->format($localizedDateFormat);
          $isoWorkDate = $datetimeWorkDay->format(CustomDateTime::getISODateFormat());

          // Set localized content.
          $curLanguage_shortEntry = sprintf('CUSTOM_DATETIME_WEEKDAY_SHORT_%d', $daySeq);
          $curLanguageEntry = sprintf('CUSTOM_DATETIME_WEEKDAY_%d', $daySeq);
          if ($languagefileHandler->doesLanguageEntryExists($curLanguageEntry)) {
            // Check if there are any registration on date of isoWorkDate for the employee
            if (array_key_exists($isoWorkDate, $arrRegisteredData)) {
              $arrWeekdays[$daySeq] = ['weekday_name' => $languagefileHandler->getEntryContent($curLanguageEntry),
                                       'weekday_name_short' => $languagefileHandler->getEntryContent($curLanguage_shortEntry),
                                       'work_date' => $workDate,
                                       'iso_work_date' => $isoWorkDate,
                                       'timesheet_uuid' => $arrRegisteredData[$isoWorkDate]["timesheet_uuid"],
                                       'hours_regular' => $arrRegisteredData[$isoWorkDate]['timesheet_hours_regular'],
                                       'hours_overtime' => $arrRegisteredData[$isoWorkDate]['timesheet_hours_overtime'],
                                       'hours_break' => $arrRegisteredData[$isoWorkDate]['timesheet_hours_break']
                                      ];
            } else {
              $arrWeekdays[$daySeq] = ['weekday_name' => $languagefileHandler->getEntryContent($curLanguageEntry),
                                       'weekday_name_short' => $languagefileHandler->getEntryContent($curLanguage_shortEntry),
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

        // Get localized number-formater.
        $numberFormater = NumberFormatter::create($languagefileHandler->getLanguageIdent(), NumberFormatter::DECIMAL);

        // Page meta-data is different from supported language.
        // @TODO: Retrive page meta-data stored in the database.
        $pageTitle = 'Weekly Timesheets'; // Page-title is stored in the menu, I just need to create a menu for the back-end.
        $pageMetaDescription = 'Weekly Timesheet administration'; // Also added to the menu-structore
        $pageMetaKeywords = 'Timesheets, Weekly, Your used time, Administation'; // Also added to the menu-structure.

        $template = Template::getInstance('timesheet_register_weekly.tpl', Template::PATH_TEMPLATES_MODULE);
        // Send the variables to the template.
        $template->assign('employeeUuid', $p_employeeUUID);
        $template->assign('yearNumber', $yearNumber);
        $template->assign('weekNumber', $weekNumber);
        $template->assign('weekDateStart', $datetimeWeekDateStart->format($localizedDateFormat));
        $template->assign('weekDateEnd', $datetimeWeekDateEnd->format($localizedDateFormat));
        $template->assign('arrWeekdays', $arrWeekdays);
        $template->assign('focusDay', $p_focusDay);
        $template->assign('arrAccumulatedHours_week', $arrAccumulatedHours_week[0]);
        $template->assign('totalHours_week', array_sum($arrAccumulatedHours_week[0]));
        $template->assign('arrAccumulatedHours_annualy', $arrAccumulatedHours_annualy[0]);
        $template->assign('totalHours_annualy', array_sum($arrAccumulatedHours_annualy[0]));
        $template->assign('arrOptions_weeks', $arrOptions_weeks);
        $template->assign('arrLocalizedMonths', $arrLocalizedMonths);
        $template->assign('decimalSeparator', $numberFormater->getSymbol(NumberFormatter::DECIMAL_SEPARATOR_SYMBOL));
        $template->assign('arrConfigPaths', Template::getTemplatePaths());
//        $template->assign('flashMessage', FlashMessage::getInstance('Your data was saved successfully ...', FlashMessage::MESSAGE_TYPE_SUCCESS));
        $template->assign('resumeSessionId', session_id());
        try {
          $templateOutput = $template->fetch();
          $this->displayAsPage($pageTitle, $pageMetaDescription, $pageMetaKeywords, $templateOutput);
        } catch (Exception $e) {
          $this->displayAsPage($pageTitle, $pageMetaDescription, $pageMetaKeywords, $e->getMessage());
        }
    }
}