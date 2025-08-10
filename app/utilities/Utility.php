<?php

/**
 * General-purpose utility class with static methods and variables used across the application
 * app/config/Utility.php
 * should be required in bootstrap.php to make them accessible globally
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

class Utility
{

}