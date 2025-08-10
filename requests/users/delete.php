<?php
// app/requests/users/update.php

require_once __DIR__ . '/../../app/config/bootstrap.php';
require_once REQUESTS_PATH . '/users/User.php';

function delete()
{
    $id = clean_input($_GET['id']);
    if (!is_numeric($id) || !$id) {
        http_response_code(400);
        return_response("Invalid id", [], 422);
        header('Location: ' . base_url());
        exit;
    }

    $user = new User();

    $user->delete($id);

    header('Location: ' . base_url());
}


/* * Main entry point for the clients API
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    delete();  // You can make this dynamic later
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
