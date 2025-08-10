<?php
// app/requests/users/update.php

require_once __DIR__ . '/../../app/config/bootstrap.php';
require_once REQUESTS_PATH . '/users/User.php';


function update()
{

    $user = new User();

    $id = clean_input($_POST['id'] ?? '');

    $data = $_POST;

    $response = $user->update($id, $data);

    if ($response['status'] === 'success') {
        header('Location: ' . base_url('users/update.php?id=' . $id));
        exit;
    } else {
        header('Location: ' . base_url("users/update.php?id={$id}"));
        exit;
    }
}


/* * Main entry point for the clients API
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    update();  // You can make this dynamic later
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
