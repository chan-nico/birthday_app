<?php

require_once '../conf/const2.php';
require_once '../model/validation.php';

session_start();
$session_name = session_name();
$_SESSION = [];

if (isset($_COOKIE['$session_name'])) {
    $params = session_get_cookie_params();
    setcookie(
        $session_name,
        '',
        time() -42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponry"]
    );
}

session_destroy();
header(location_func() . 'login.php');
exit;
