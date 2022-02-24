<?php

require_once '../conf/const.php';
require_once '../model/validation.php';
require_once '../model/db_function.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header(location_func() . 'login.php');
    exit;
}

$user_id = '';
$user_name = '';
$select_month = '';
$list_id = '';
$list_array = [];
$result_msg = '';
$month_list = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
$csrf_token = '';

$link = db_connect();
$user_id = $_SESSION['user_id'];
$user_name = get_user_name($link, $user_id);
$user_name = entity_str($user_name);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        if (isset($_POST['select_month'])) {
            $select_month = $_POST['select_month'];
            $list_array = get_list($link, $user_id, $select_month);
            $list_array = entity_array($list_array);
        }
    
        if ((isset($_POST['delete_flag'])) && (isset($_POST['list_id']))) {
            if ($_POST['delete_flag'] === 'true') {
                $list_id = $_POST['list_id'];
                $result_msg = delete_list($link, $user_id, $list_id);
                $list_array = get_list_all($link, $user_id);
                $list_array = entity_array($list_array);
            }
        }
        $csrf_token = get_csrf_token();
        $_SESSION['csrf_token'] = $csrf_token;
    } else {
        header(location_func() . 'logout.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $list_array = get_list_all($link, $user_id);
    $list_array = entity_array($list_array);
    
    $csrf_token = get_csrf_token();
    $_SESSION['csrf_token'] = $csrf_token;
}

$link = db_close($link);

include_once '../view/main_page.php';
