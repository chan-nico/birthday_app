<?php

require_once '../conf/const2.php';
require_once '../model/validation.php';
require_once '../model/db_function.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header(location_func() . 'login.php');
    exit;
}

$user_name = '';
$list_id = '';
$suc_msg = '';
$temp_file = '';
$file_name = '';
$ext = '';
$img_name = '';
$name = '';
$year = 0;
$month = 0;
$day = 0;
$list_data = [];
$err_msg = [];
$month_list = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
$csrf_token = '';

$link = db_connect();
$user_id = $_SESSION['user_id'];
$user_name = get_user_name($link, $user_id);
$user_name = entity_str($user_name);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        if (isset($_POST['edit_flag']) && (isset($_POST['list_id']))) {
            if ($_POST['edit_flag'] === 'true') {
                $list_id = $_POST['list_id'];
    
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
                    $result =
                        edit_list($link, $user_id, $list_id, $name, $year, $month, $day, $comment, $temp_file, $ext);
                    if ($result === true) {
                        $suc_msg = 'リストの編集に成功しました';
                    } else {
                        $err_msg[] = 'リストの編集に失敗しました';
                    }
                }
                $list_data = get_list_data($link, $user_id, $list_id);
                $list_data = entity_array($list_data);
        
                $img_name = $list_data['img_name'];
                $name = $list_data['name'];
                $comment = $list_data['comment'];
                $json_year = json_encode($year);
                $json_month = json_encode($month);
                $json_date = json_encode($day);
            }
        } elseif (isset($_POST['list_id'])) {
            $list_id = $_POST['list_id'];
            $list_data = get_list_data($link, $user_id, $list_id);
            $list_data = entity_array($list_data);
    
            $img_name = $list_data['img_name'];
            $name = $list_data['name'];
            $comment = $list_data['comment'];
            $json_year = json_encode($list_data['year']);
            $json_month = json_encode($list_data['month']);
            $json_date = json_encode($list_data['day']);
        }
    } else {
        header(location_func() . 'logout.php');
        exit;
    }
}

$csrf_token = get_csrf_token();
$_SESSION['csrf_token'] = $csrf_token;

$link = db_close($link);

include_once '../view/edit_page.php';
