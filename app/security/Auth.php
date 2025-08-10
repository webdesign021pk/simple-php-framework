<?php

/**
 * Simple Authentication class for basic user authentication. 
 * Provides rules such as user details, login, logout, isAuthenticated, etc.
 * app/config/Auth.php
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

class Auth
{
    protected $db;

    public function __construct()
    {
        $this->db = DB::connection();
    }

    public static function user()
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $stmt = DB::connection()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function isAuthenticated(): bool
    {
        return self::user() !== null;
    }

    public function login($request)
    {
        // Define validation rules
        $rules = [
            'username' => ['required'],
            'password' => ['required'],
        ];

        // Perform validation
        $validator = new Validator($request);
        if (!$validator->validate($rules)) {
            flash('old', $request); // $request is your validated or original input
            Message::error('Auth@login', 'Validation failed', $validator->getErrors());
            return return_response("Validation failed", $validator->getErrors(), 422);
        }

        // Get validated data (optionally filtered)
        $request = $validator->getValidatedData();

        $username = ($request['username']);
        $password = ($request['password']);

        log_event("Login attempt for username: $username");

        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            session_regenerate_id(false); // Prevent session fixation

            $redirect_url = $_SESSION['redirect_url'] ?? base_url('index.php');
            return return_response("Login successful.", ['redirect_url' => $redirect_url], 200);
        } else {
            Message::error('Auth@login', 'Invalid username or password');
            return return_response("Invalid username or password.", 422);
        }
    }

    public function logout()
    {
        $_SESSION = [];
        session_unset();
        session_destroy();

        return return_response("Logout successful.", $_SESSION, 200);
    }
}
