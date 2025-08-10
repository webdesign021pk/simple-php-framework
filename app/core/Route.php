<?php

/**
 * Route class for handling api routing in the application.
 * app/config/Route.php
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

class Route
{
    private static array $routes = [];

    public static function get(string $path, callable|array $handler)
    {
        self::add('GET', $path, $handler);
    }

    public static function post(string $path, callable|array $handler)
    {
        self::add('POST', $path, $handler);
    }

    public static function put(string $path, callable|array $handler)
    {
        self::add('PUT', $path, $handler);
    }

    public static function delete(string $path, callable|array $handler)
    {
        self::add('DELETE', $path, $handler);
    }

    public static function add(string $method, string $path, callable|array $handler)
    {
        self::$routes[$method][$path] = $handler;
    }

    public static function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $script = $_SERVER['SCRIPT_NAME'];

        // Handle rewritten URLs (api/... -> api.php?route=...)
        if (isset($_GET['route'])) {
            $relative = $_GET['route'];
        } else {
            $base = dirname($script);
            $relative = str_replace($base, '', $uri);
        }

        $relative = '/' . ltrim($relative, '/');

        foreach (self::$routes[$method] ?? [] as $route => $handler) {
            $pattern = "@^" . preg_replace('/\{([^}]+)\}/', '(?P<\1>[^/]+)', $route) . "$@";

            if (preg_match($pattern, $relative, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // ✅ Extract body data
                $requestData = [];
                $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

                if ($method === 'POST') {
                    $requestData = $_POST;
                } elseif (in_array($method, ['PUT', 'DELETE', 'PATCH'])) {
                    if (strpos($contentType, 'application/json') !== false) {
                        $rawInput = file_get_contents("php://input");
                        $requestData = json_decode($rawInput, true) ?? [];
                    } else {
                        parse_str(file_get_contents("php://input"), $requestData);
                    }
                }

                // ✅ Merge params and body
                $finalArgs = array_merge(array_values($params), [$requestData]);

                if (is_array($handler)) {
                    [$class, $methodName] = $handler;
                    $instance = new $class();
                    $result = call_user_func_array([$instance, $methodName], $finalArgs);
                } else {
                    $result = call_user_func_array($handler, $finalArgs);
                }

                // Output response
                if (!headers_sent()) {
                    header('Content-Type: application/json');
                }

                echo is_array($result) ? json_encode($result) : $result;
                exit;
            }
        }

        // Route not found
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Route not found',
            'method' => $method,
            'uri' => $uri,
            'script' => $script
        ]);
    }
}
