<?php
// register.php (for admin only)

require_once __DIR__ . '/../../app/config/bootstrap.php';
require_once REQUESTS_PATH . '/users/User.php';

// Call the Auth class
$user = new User();

// first find the admin user by username
$firstUser = $user->all();

if ($firstUser && count($firstUser) > 0) {

    return_response('User already exists. Please log in.');
    header('Location: ' . base_url('auth/login.php'));
    
} else {
    $data = [
        'full_name' => 'Admin',
        'username' => 'admin',
        'email' => 'admin@domain.name',
        'password' => 'Shift!2022',
        'role' => 'admin',
    ];

    $response = $user->create($data);

    return_response('User created. Please log in.');
    header('Location: ' . base_url('auth/login.php'));
}
