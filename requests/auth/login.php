<?php
require_once __DIR__ . '/../../app/config/bootstrap.php';

$auth = new Auth();

$data = $_POST;

$response = $auth->login($data);

// Process response (for browser-based frontend, not JS fetch)
if ($response['status'] === 'success') {
    $redirect = $response['data']['redirect_url'] ?? base_url('index.php');
    header('Location: ' . $redirect);
    exit;
} else {
    header('Location: ' . base_url('auth/login.php'));
    exit;
}
