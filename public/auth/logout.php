<?php
// logout.php
require '../../app/config/bootstrap.php';

$auth = new Auth();
$response = $auth->logout();

header('Location: ' . base_url());
exit;
