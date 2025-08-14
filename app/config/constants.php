<?php

/**
 * Defines global constants for the application
 * app/config/constants.php
 * should be included in bootstrap.php to make them accessible globally
 * 
 * These are global constants and follow the naming convention of 'CONSTANT_NAME' 
 * using UPPER_SNAKE_CASE for clarity and consistency.
 * 
 * @package App
 * @author rrafiq
 * @version 1.0.0
 * @license MIT
 */

/** Error handling ************************************************************************************* */

declare(strict_types=1); // For errors, Strict error reporting for development

error_reporting(E_ALL); // Report all errors

ini_set('display_errors', '1'); // display errors for development

if (php_sapi_name() !== 'cli' && basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    http_response_code(403);
    exit("403 Forbidden");
}

/** **************************************************************************************************** */




/** Global Constants *********************************************************************************** */

define('FOLDER_ON_DOMAIN', '/simple-php-framework/public'); // Current folder on domain - if project is hosted on mydomain.abc/project-name then this will be '/project-name'

define('PUBLIC_FOLDER', '/simple-php-framework/public'); // Current folder on domain - if project is hosted on mydomain.abc/project-name then this will be '/project-name'

define('REQUESTS_FOLDER', '/simple-php-framework/requests'); // Current folder on domain - if project is hosted on mydomain.abc/project-name then this will be '/project-name'

define('APP_NAME', 'simple-php-framework'); // Determine the current page name - example: index.php or about.php or contact.php

/** **************************************************************************************************** */




/** DO NOT EDIT BELOW THIS LINE ************************************************************************ */

define('PROJECT_ROOT', dirname(__DIR__, 2)); // Project root - Goes up from 'app/config' to project root i.e => C:/xampp/htdocs/project-name

define('APP_ROOT', PROJECT_ROOT . '/app'); // Application root - C:/xampp/htdocs/project-name/app

define('CONFIG_PATH', APP_ROOT . '/config'); // Config folder - C:/xampp/htdocs/project-name/app/config

define('SECURITY_PATH', APP_ROOT . '/security'); // Security folder - C:/xampp/htdocs/project-name/app/security

define('CORE_PATH', APP_ROOT . '/core'); // Core folder - C:/xampp/htdocs/project-name/app/core

define('UTILITIES_PATH', APP_ROOT . '/utilities'); // Config folder - C:/xampp/htdocs/project-name/app/config

define('LOGS_PATH', APP_ROOT . '/logs'); // Logs folder - C:/xampp/htdocs/project-name/app/logs

define('REQUESTS_PATH', PROJECT_ROOT . '/requests'); // Requests folder - C:/xampp/htdocs/project-name/app/requests

define('DOMAIN', $_SERVER['HTTP_HOST'] ?? 'localhost'); // Current domain name

define('PROTOCOL', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://'); // Current protocol - http or https

define('BASE_URL', PROTOCOL . DOMAIN . PUBLIC_FOLDER); // Base URL - http://mydomain.abc/project-name or https://mydomain.abc/project-name - This will be use throughout the application

define('REQUEST_URL', PROTOCOL . DOMAIN . REQUESTS_FOLDER); // Base URL - http://mydomain.abc/project-name or https://mydomain.abc/project-name - This will be use throughout the application

define('CURRENT_PAGE', basename($_SERVER['PHP_SELF'])); // Determine the current page name - example: index.php or about.php or contact.php

define('CURRENT_PATH', ltrim(str_replace(FOLDER_ON_DOMAIN . '/', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), '/'));

define('API_BASE_PATH', REQUESTS_PATH . '/api'); // API base path


/** **************************************************************************************************** */




/** Security headers *********************************************************************************** */

header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");

/** **************************************************************************************************** */
