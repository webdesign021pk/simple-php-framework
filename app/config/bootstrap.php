<?php

/**
 * Bootstrap file for the application
 * app/config/bootstrap.php
 * should be required in all files (directly or indirectly)
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

/** Loading Global Constants & Variables *************************************************************** */

// Global constants
try {
    if (!file_exists(__DIR__ . '/constants.php')) {
        throw new RuntimeException("Constants file not found");
    }
    require_once __DIR__ . '/constants.php';
} catch (Throwable $e) {
    error_log("Bootstrap error: " . $e->getMessage());
    http_response_code(500);
    die("System initialization failed. Please try again later.");
}

// Global variables
try {
    if (!file_exists(__DIR__ . '/variables.php')) {
        throw new RuntimeException("Variables file not found");
    }
    require_once __DIR__ . '/variables.php';
} catch (Throwable $e) {
    error_log("Bootstrap error: " . $e->getMessage());
    http_response_code(500);
    die("System initialization failed. Please try again later.");
}
/** **************************************************************************************************** */




/** Loading composer autoload ************************************************************************** */

// autoload.php
try {
    if (!file_exists(__DIR__ . '/../../vendor/autoload.php')) {
        throw new RuntimeException("Constants file not found");
    }
    require_once __DIR__ . '/../../vendor/autoload.php';
} catch (Throwable $e) {
    error_log("Bootstrap error: " . $e->getMessage());
    http_response_code(500);
    die("System initialization failed. Please try again later.");
}

/** **************************************************************************************************** */




/** User Session configuration ************************************************************************* */

// Session configuration
session_set_cookie_params([
    'lifetime' => 86400, // 1 day
    'path' => FOLDER_ON_DOMAIN,
    'domain' => DOMAIN,
    'secure' => isset($_SERVER['HTTPS']), // Auto-enable for HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();


// Session flash messages
if (!isset($_SESSION['_flash'])) {
    $_SESSION['_flash'] = [];
}
$_SESSION['_old_flash'] = $_SESSION['_flash'] ?? [];
$_SESSION['_flash'] = [];



/** **************************************************************************************************** */




/** Loading Database class file ************************************************************************ */

try {
    if (!file_exists(CORE_PATH . '/Database.php')) {
        throw new RuntimeException("Database file not found");
    }
    require_once CORE_PATH . '/Database.php';
} catch (Throwable $e) {
    error_log("Bootstrap error: " . $e->getMessage());
    http_response_code(500);
    die("System initialization failed. Please try again later.");
}

/** **************************************************************************************************** */




/** Loading helper functions file ********************************************************************** */

try {
    if (!file_exists(CONFIG_PATH . '/helper.php')) {
        throw new RuntimeException("Helper file not found");
    }
    require_once CONFIG_PATH . '/helper.php';
} catch (Throwable $e) {
    error_log("Bootstrap error: " . $e->getMessage());
    http_response_code(500);
    die("System initialization failed. Please try again later.");
}

/** **************************************************************************************************** */




/** Loading Auth class file ********************************************************************** */

try {
    if (!file_exists(SECURITY_PATH . '/Auth.php')) {
        throw new RuntimeException("Auth class file not found");
    }
    require_once SECURITY_PATH . '/Auth.php';
} catch (Throwable $e) {
    error_log("Bootstrap error: " . $e->getMessage());
    http_response_code(500);
    die("System initialization failed. Please try again later.");
}

/** **************************************************************************************************** */




/** Loading Permission class file ********************************************************************** */

try {
    if (!file_exists(SECURITY_PATH . '/Permission.php')) {
        throw new RuntimeException("Permission class file not found");
    }
    require_once SECURITY_PATH . '/Permission.php';
} catch (Throwable $e) {
    error_log("Bootstrap error: " . $e->getMessage());
    http_response_code(500);
    die("System initialization failed. Please try again later.");
}

/** **************************************************************************************************** */




/** Authentication and authorization ******************************************************************* */

// first check if user is in session and is real user from database
$user = null;

if (isset($_SESSION['user_id'])) {
    if (!Auth::isAuthenticated()) {
        $_SESSION = [];
        session_unset();
        session_destroy();

        // if the request has header of application/json then return json response
        if (is_json_request()) {
            return_response_as_json("You are not logged in", [], 401);
            exit;
        }

        header('Location: ' . BASE_URL . '/auth/login.php'); // login page
        exit;
    }
}

// Redirect logged-in users away from private pages
if (in_array(CURRENT_PATH, $protected_paths)) {
    if (!Auth::isAuthenticated()) {
        // if the request has header of application/json then return json response
        if (is_json_request()) {
            return_response_as_json("You are not logged in", [], 401);
            exit;
        }
        header('Location: ' . BASE_URL . '/auth/login.php'); // login page
        exit;
    }
}

if (in_array(CURRENT_PATH, $blocked_paths_if_authenticated) && $user) {
    // if the request has header of application/json then return json response
    if (is_json_request()) {
        return_response_as_json("Already logged in", [], 401);
        exit;
    }
    header('Location: ' . BASE_URL . '/'); // home page
    exit;
}

/** **************************************************************************************************** */




/** Loading Validator class file *********************************************************************** */

try {
    if (!file_exists(UTILITIES_PATH . '/Validator.php')) {
        throw new RuntimeException("Validator file not found");
    }
    require_once UTILITIES_PATH . '/Validator.php';
} catch (Throwable $e) {
    error_log("Bootstrap error: " . $e->getMessage());
    http_response_code(500);
    die("System initialization failed. Please try again later.");
}

/** **************************************************************************************************** */




/** Loading Message class file *********************************************************************** */

try {
    if (!file_exists(UTILITIES_PATH . '/Message.php')) {
        throw new RuntimeException("Message file not found");
    }
    require_once UTILITIES_PATH . '/Message.php';
} catch (Throwable $e) {
    error_log("Bootstrap error: " . $e->getMessage());
    http_response_code(500);
    die("System initialization failed. Please try again later.");
}

/** **************************************************************************************************** */
