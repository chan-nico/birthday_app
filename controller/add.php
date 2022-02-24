<?php

require_once '../conf/const.php';
require_once '../model/validation.php';
require_once '../model/db_function.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header(location_func() . 'login.php');
    exit;
}

$user_name = '';
$user_id = '';
$temp_file = '';
$file_name = '';
$ext = '';
$img_name = '';
$name = '';
$year = 0;
$month = 0;
$day = 0;
$suc_msg = '';
$add_data = [];
$err_msg = [];
$month_list = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
$csrf_token = '';

$user_id = $_SESSION['user_id'];
$link = db_connect();
$user_name = get_user_name($link, $user_id);
$user_name = entity_str($user_name);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        if ($_FILES['image']['name'] !== '') {
            $temp_file = $_FILES['image']['tmp_name'];
            $file_name = $_FILES['image']['name'];
            $err_msg[] = file_check($temp_file, $file_name, $ext);
        }
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
        }
        if (isset($_POST['year'])) {
            $year = $_POST['year'];
            $year = (int)$year;
        }
        if (isset($_POST['month'])) {
            $month = $_POST['month'];
            $month = (int)$month;
        }
        if (isset($_POST['day'])) {
            $day = $_POST['day'];
            $day = (int)$day;
        }
        if (isset($_POST['comment'])) {
            $comment = $_POST['comment'];
        }
    
        // エラーチェック
        $err_msg[] = name_check($name);
        $err_msg[] = birthday_check($month, $day);
        $err_msg[] = comment_check($comment);
        $err_msg = array_filter($err_msg);
    
        if (empty($err_msg)) {
            $result = add_list($link, $user_id, $name, $year, $month, $day, $comment, $temp_file, $ext);
            if ($result === true) {
                $suc_msg = 'リストの追加に成功しました';
            } else {
                $err_msg[] = 'リストの追加に失敗しました';
            }
        }
    
        $json_year = json_encode($year);
        $json_month = json_encode($month);
        $json_date = json_encode($day);
    } else {
        header(location_func() . 'logout.php');
        exit;
    }
}

$csrf_token = get_csrf_token();
$_SESSION['csrf_token'] = $csrf_token;

$link = db_close($link);

include_once '../view/add_page.php';
