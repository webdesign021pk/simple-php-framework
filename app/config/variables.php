<?php

/**
 * Defines global variables and arrays for the application
 * app/config/variables.php
 * should be required in bootstrap.php to make them accessible globally
 * 
 * These are global variables and follows the naming convention of 'variable_name' 
 * as snake_case for consistency and readability.
 * 
 * @package App
 * @author rrafiq
 * @version 1.0.0
 * @license MIT
 */

if (php_sapi_name() !== 'cli' && basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    http_response_code(403);
    exit("403 Forbidden");
}

/** **************************************************************************************************** */

// Paths which logged-in users should NOT access (e.g., login or register)
$blocked_paths_if_authenticated = [
    'auth/login.php',
    'register.php',
    'forget-password.php'
];


// Paths that are protected
$protected_paths = [
    'users/index.php',
    'users/create.php',
    'users/update.php',
    'users/delete.php',
];

// Pages not listed in either array will be considered "public"

/** **************************************************************************************************** */


/** **************************************************************************************************** */
$page_title = ""; // Dynamic page title

$navigation_links = []; // Dynamic navigation
