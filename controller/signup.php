<?php

require_once '../conf/const.php';
require_once '../model/validation.php';
require_once '../model/db_function.php';

$user_name = '';
$password = '';
$suc_msg = '';
$err_msg = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = db_connect();
    if (isset($_POST['user_name'])) {
        $user_name = $_POST['user_name'];
        $err_msg[] = user_name_check($link, $user_name);
    }
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        $err_msg[] = password_check($password);
    }
    $err_msg = array_filter($err_msg);
    if (empty($err_msg)) {
        $suc_msg = insert_user($link, $user_name, $password);
        if ($suc_msg !== true) {
            $err_msg[] = '新規ユーザー登録に失敗しました';
        }
    }
    db_close($link);
}

include_once '../view/signup_page.php';
