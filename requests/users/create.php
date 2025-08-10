<?php
// app/requests/users/update.php

require_once __DIR__ . '/../../app/config/bootstrap.php';
require_once REQUESTS_PATH . '/users/User.php';


function create()
{

    $user = new User();

    $data = $_POST;

    $user->create($data);

    header('Location: ' . base_url("users/create.php"));
}


/* * Main entry point for the clients API
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    create();  // You can make this dynamic later
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
