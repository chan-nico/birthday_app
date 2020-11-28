<?php

require_once '../conf/const2.php';
require_once '../model/validation.php';
require_once '../model/db_function.php';

$user_name = '';
$password = '';
$err_msg = [];
$csrf_token = '';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (isset($_SESSION['user_id'])) {
        header(location_func() . 'main.php');
        exit;
    }

    $csrf_token = get_csrf_token();
    $_SESSION['csrf_token'] = $csrf_token;

    if (isset($_SESSION['login_err_flag'])) {
        $login_err_flag = $_SESSION['login_err_flag'];
        $_SESSION['login_err_flag'] = false;
    } else {
        $login_err_flag = false;
    }

    if (isset($_SESSION['err_msg'])) {
        $err_msg = $_SESSION['err_msg'];
        $_SESSION['err_msg'] = [];
    } else {
        $err_msg = [];
    }

    if (isset($_COOKIE['bdapp_user_name'])) {
        $user_name = $_COOKIE['bdapp_user_name'];
        $user_name = entity_str($user_name);
    } else {
        $user_name = '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        if (isset($_POST['user_name'])) {
            $user_name = $_POST['user_name'];
            setcookie(
                'bdapp_user_name',
                $user_name,
                time() + 60 * 60 * 24
            );
            if ($user_name === '') {
                $err_msg[] = 'ユーザー名を入力してください';
            }
        }
    
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
            if ($_POST['password'] === '') {
                $err_msg[] = 'パスワードを入力してください';
            }
        }
    
        if (empty($err_msg)) {
            $link = db_connect();
            $result = get_user_id(
                $link,
                $user_name,
                $password
            );
            db_close($link);
    
            if (isset($result['user_id'])) {
                $_SESSION['user_id'] = $result['user_id'];
                header(location_func() . 'main.php');
                exit;
            } else {
                $_SESSION['login_err_flag'] = true;
                header(location_func() . 'login.php');
                exit;
            }
        } else {
            $_SESSION['login_err_flag'] = true;
            $_SESSION['err_msg'] = $err_msg;
            header(location_func() . 'login.php');
            exit;
        }
    } else {
        
    }
}

include_once '../view/login_page.php';
