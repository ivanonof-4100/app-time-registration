<?php
namespace App\Modules\Timesheets\Classes\Controller;

use Common\Classes\Controller\ApiController;
use Common\Classes\StdApp;
use Common\Classes\Url;
use Common\Classes\InputHandler;
use Common\Classes\ResponseCode;
use App\Modules\Timesheets\Classes\Model\Timesheet;
use Common\Classes\Datetime\CustomDateTime;

/**
 * Filename     : timesheet_api_controller.class.php
 * Language     : PHP v7.4+
 * Date created : Ivan, 24/01-2025
 * Last modified: Ivan, 24/01-2025
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * @copyright: Copyright (C) 2025 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * This class handles the flow-control in using the REST API for Timesheets.
 */
class TimesheetApiController extends ApiController {
    public function __construct(string $p_lang =APP_LANGUAGE_IDENT, string $p_charset ='utf8', StdApp $p_appInstance) {
        parent::__construct($p_appInstance);
    }

    public function __destruct() {
        parent::__destruct();
    }

    /**
     * @param string $p_lang
     * @param string $p_charset Default 'utf8'
     * @param StdApp $p_appInstance
     * @return TimesheetApiController
     */
    public static function getInstance(string $p_lang ='da', string $p_charset ='utf8', StdApp $p_appInstance) : TimesheetApiController {
        return new TimesheetApiController($p_lang, $p_charset, $p_appInstance);
    }

    /**
     * CRUD Read-operation.
     * @return void
     */
    public function handleRequest_readList() : void {
        $url = Url::getInstance();
        // Make sure that only GET-method can access it, even though the route-handler also does this.
        if (!$url->isRequestMethod_GET()) {
          $this->sendHttpResponse(ResponseCode::HTTP_METHOD_NOT_ALLOWED);
        } else {
          // Connect to DB
          $this->initalizeDependencies();
          // Get the active database-connection from the codebase-registry.
          $codebaseRegistry = $this->getInstance_codebaseRegistry();
          $dbAbstractionInstance = $codebaseRegistry->getInstance_dbConnection();

          // Retrive parameter-data.
          $inputHandler = InputHandler::getInstance();
          $arrInputParam_employeeUUID = $inputHandler->retriveInputParameter('employee_uuid', InputHandler::ACCEPTED_DATATYPE_UUID, InputHandler::INPUT_SOURCE_GET);
          $arrInputParam_year = $inputHandler->retriveInputParameter('year', InputHandler::ACCEPTED_DATATYPE_POS_INT, InputHandler::INPUT_SOURCE_GET);

          // Set from and to- ISO-dates,
          if ($arrInputParam_year['is_set'] && $arrInputParam_year['is_valid']) {
            $yearFromDate = CustomDateTime::getInstance();
            $yearFromDate->setDate_toYearStart($arrInputParam_year['value']);
            $yearToDate = CustomDateTime::getInstance();
            $yearToDate->setDate_toYearEnd($arrInputParam_year['value']);
          } else {
            // When not given a specefic year it will be the current year.
            $yearFromDate = CustomDateTime::getInstance();
            $yearFromDate->setDate_toYearStart();
            $yearToDate = CustomDateTime::getInstance();
            $yearToDate->setDate_toYearEnd();
          }

          // Fetch data from the datebase.
          if ($arrInputParam_employeeUUID['is_set'] && $arrInputParam_employeeUUID['is_valid']) {
            $arrRows = Timesheet::retriveRegisteredData_asAssocArray($dbAbstractionInstance, $arrInputParam_employeeUUID['value'], $yearFromDate->getFormatedISODate(), $yearToDate->getFormatedISODate());
            // Send HTTP response status-code
            $this->sendJSONResponse(ResponseCode::HTTP_OK, $arrRows);
          } else {
            // Without this parameter the API will not be able to handle the request.
            $this->sendHttpResponse(ResponseCode::HTTP_BAD_REQUEST, 'Mandatory parameter employee_uuid was missing or not valid');
          }
        }
    }
}