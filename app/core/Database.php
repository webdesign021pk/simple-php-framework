<?php

/**
 * Database class for managing PDO connection to the preffered database.
 * app/config/Database.php
 * Should be included in bootstrap.php to make it accessible globally
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

class DB
{
    /** @var PDO|null */
    private static ?PDO $instance = null;

    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct() {}

    /**
     * Returns a singleton PDO instance.
     *
     * @return PDO
     */
    public static function connection(): PDO
    {
        if (self::$instance === null) {
            try {
                $dbFile = __DIR__ . '/../../database/database.sqlite';
                self::$instance = new PDO("sqlite:" . $dbFile);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Connection failed: ' . $e->getMessage()
                ]);
                exit;
            }
        }

        return self::$instance;
    }
}
