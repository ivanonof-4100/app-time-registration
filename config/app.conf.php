<?php
const APP_DEBUG_MODE = FALSE;
const APP_SECURITY_TOKEN = 'n1mFGz2XGh9VE1so7DmEEYubQ065OsRxYsEVonJp';
// const APP_ROOT_PATH = __DIR__ .'/../';
const APP_ROOT_PATH = '/data/WebProjects/Northern-partners/Projekt-timeseddler/app-time-registration/';
const CODEBASE_ROOT_PATH = APP_ROOT_PATH .'my_codebase/';

const APP_LANGUAGE_IDENT ='da';
const APP_DEFAULT_CHARSET = 'utf8';
const APP_DOMAIN_TITLE = '%s | Northern Partners';
const SITE_DOMAIN_NAME = 'northern-partners.eu';
const PATH_TEMPLATES_DOMAIN = APP_ROOT_PATH .'view/templates/site_templates/';
const APP_LOG_PATH = APP_ROOT_PATH .'log/';
const APP_LOGFILE = APP_LOG_PATH .'app-errors.log';
// Path to language-files
const PATH_LANGUAGE = CODEBASE_ROOT_PATH .'language/';

// MySQL Database
const SITE_HAS_DB = TRUE;
const DB_HOST = 'localhost';
const DB_NAME = 'np_timesheets';
const DB_CODEPAGE = 'utf8mb3';
const RDBMS_USER_NAME = 'dbusr_web_np';
const RDBMS_USER_PASSWD = 'kBsjlkS029-z4';