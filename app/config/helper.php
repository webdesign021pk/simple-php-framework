<?php

/**
 * General-purpose utility functions used across the application. Includes logging, string helpers, and reusable logic.
 * app/config/helper.php
 * should be required in bootstrap.php to make them accessible globally
 * 
 * These are global helpers and follows the naming convention of 'function_name' 
 * as snake_case for consistency and readability.
 * 
 * @package     App
 * @author      rrafiq
 * @version     1.0.0
 * @license     MIT
 */

if (php_sapi_name() !== 'cli' && basename($_SERVER['SCRIPT_FILENAME']) === basename(__FILE__)) {
    http_response_code(403);
    exit("403 Forbidden");
}

require_once __DIR__ . '/../utilities/Message.php';

/** Logging Helpers ************************************************************************************ */
function log_event($message, $file_name = null)
{
    error_log("[$file_name] " . date("Y-m-d H:i:s") . " - " . $message . PHP_EOL, 3, LOGS_PATH . "/error_log");
}
/** **************************************************************************************************** */




/** Returned Data or Error Messages ******************************************************************** */

// return response as json with status, code, message and data
function return_response_as_json($message = null, $data = [], $code = null): void
{
    if ($code) http_response_code($code);

    if (!headers_sent()) {
        header('Content-Type: application/json');
    }

    $is_success = str_starts_with((string)$code, '2');

    $response = [
        'status' => $is_success ? 'success' : 'error',
        'message' => $message,
        'code' => $code,
        $is_success ? 'data' : 'errors' => $data
    ];

    echo json_encode($response);
    exit; // << ensures script ends here
}

// return response as array with status, code, message and data
function return_response($message, $data = [], $code = 400)
{
    if ($code) http_response_code($code);

    $is_success = str_starts_with((string)$code, '2');

    $response = [
        'status' => $is_success ? 'success' : 'error',
        'message' => $message,
        'code' => $code,
        $is_success ? 'data' : 'errors' => $data
    ];

    return $response;
}

/** **************************************************************************************************** */




/** Frontend Helpers *********************************************************************************** */

// $_Get and $_Post request cleaner and validator
function clean_input($data)
{
    if ($data === null || $data === '') return '';

    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// value getter for update form
function value($array, $key): string
{
    $old = get_flash('old');

    if (isset($old[$key])) {
        return clean_input($old[$key]);
    }

    if (isset($array[$key])) {
        return clean_input($array[$key]);
    }
    // Final fallback
    return '';
}

// value getter for create form
function old($key): string
{
    $old = get_flash('old');

    if (isset($old[$key])) {
        return clean_input($old[$key]);
    }
    // Final fallback
    return '';
}


// Flash message for next request
function flash($key, $value)
{
    $_SESSION['_flash'][$key] = $value;
}

// Retrieve flash message (and auto-clear)
function get_flash($key, $default = null)
{
    $value = $_SESSION['_old_flash'][$key] ?? $default;
    // unset($_SESSION['_old_flash'][$key]); // one-time use
    return $value;
}

/** **************************************************************************************************** */




/** API Helpers **************************************************************************************** */

function is_json_request(): bool
{
    return (
        (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) ||
        (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
    );
}

/** **************************************************************************************************** */




/** Url Helpers **************************************************************************************** */
// base url
function base_url($path = '')
{
    $baseUrl = BASE_URL; // Base URL set in constants.php
    $path = trim($path, '/');
    return $baseUrl . ($path ? "/$path" : '');
}

// php get/post/put/patch/delete request short-hand
function request_url($path = '')
{
    $baseUrl = REQUEST_URL . ''; // Base URL set in constants.php
    $path = trim($path, '/');
    return $baseUrl . ($path ? "/$path" : '');
}

// this function is used in navigiation bar to highlight current page
function is_current_path(string $path): string
{
    $base = rtrim(FOLDER_ON_DOMAIN, '/');     // remove trailing slash
    $path = '/' . ltrim($path, '/');      // ensure exactly one slash at start

    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    return ($currentPath === $base . $path) ? 'link-active' : '';
}

// redirect
function redirect_to(string $path)
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

/** **************************************************************************************************** */
