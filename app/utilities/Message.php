<?php

/**
 * Message class with static methods and variables used across the application
 * app/config/utility/Message.php
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

class Message
{
    public static string $flashKey = '_app_flash';

    /**
     * Set a flash message
     */
    public static function set(string $key, string $message, array $details = [], string $type = 'info'): void
    {
        if (!isset($_SESSION[self::$flashKey])) {
            $_SESSION[self::$flashKey] = [];
        }

        $_SESSION[self::$flashKey][$key] = [
            'message' => $message,
            'details' => $details,
            'type' => $type
        ];
    }

    public static function error(string $key, string $message, array $details = []): void
    {
        self::set($key, $message, $details, 'error');
    }

    public static function success(string $key, string $message, array $details = []): void
    {
        self::set($key, $message, $details, 'success');
    }

    public static function warning(string $key, string $message, array $details = []): void
    {
        self::set($key, $message, $details, 'warning');
    }

    public static function info(string $key, string $message, array $details = []): void
    {
        self::set($key, $message, $details, 'info');
    }

    /**
     * Get messages for a key without clearing them
     */
    public static function get(string $key): array
    {
        return $_SESSION[self::$flashKey][$key] ?? [];
    }

    public static function clear(?string $key = null, bool $clearMessage = true, bool $clearDetails = true): void
    {
        if ($key === null) {
            unset($_SESSION[self::$flashKey]);
            return;
        }

        if (!isset($_SESSION[self::$flashKey][$key])) {
            return;
        }

        if ($clearDetails) {
            $_SESSION[self::$flashKey][$key]['details'] = [];
        }
        if ($clearMessage) {
            $_SESSION[self::$flashKey][$key]['message'] = '';
            $_SESSION[self::$flashKey][$key]['type'] = '';
        }

        // Remove completely if both are cleared
        if ($clearMessage && $clearDetails) {
            unset($_SESSION[self::$flashKey][$key]);
        }
    }

    public static function clearMessage(string $key): void
    {
        self::clear($key, true, false);
    }

    public static function clearDetails(string $key): void
    {
        self::clear($key, false, true);
    }

    public static function pull(string $key, array $default = []): array
    {
        $data = self::get($key);
        if ($data) {
            self::clear($key);
        }
        return $data ?: $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[self::$flashKey][$key]);
    }


    public static function hasMessage(string $key): bool
    {
        return !empty($_SESSION[self::$flashKey][$key]['message']);
    }

    public static function getMessage(string $key, bool $clearAfter = true): ?string
    {
        if (!self::hasMessage($key)) {
            return null;
        }

        $message = self::get($key);
        // $output = htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8');

        $escapedMessage = htmlspecialchars($message['message'], ENT_QUOTES, 'UTF-8');
        $type = $message['type'] ?? 'info';

        $alertClass = match ($type) {
            'error' => 'danger',
            'success' => 'success',
            'warning' => 'warning',
            'info' => 'info',
            default => 'info'
        };

        $output = sprintf(
            '<div class="alert alert-%s alert-dismissible fade show" role="alert">
                <strong>%s!</strong> %s
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>',
            $alertClass,
            ucfirst($type),
            $escapedMessage
        );

        if ($clearAfter) {
            self::clearMessage($key);
        }

        return $output;
    }

    public static function hasDetails(string $key): bool
    {
        return !empty($_SESSION[self::$flashKey][$key]['details']);
    }

    public static function getError(string $key, string $fieldName, bool $clearAfter = true): ?string
    {
        if (!self::has($key)) {
            return null;
        }

        $message = self::get($key);
        $detail = $message['details'][$fieldName] ?? null;

        if ($detail && $clearAfter) {
            unset($_SESSION[self::$flashKey][$key]['details'][$fieldName]);
        }

        return $detail ? htmlspecialchars($detail, ENT_QUOTES, 'UTF-8') : null;
    }
}
