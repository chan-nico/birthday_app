<?php
// CSRF
function get_csrf_token()
{
    $token_length = 16;
    $bytes = openssl_random_pseudo_bytes($token_length);
    return bin2hex($bytes);
}

// header
function location_func()
{
    return 'Location: https://birthdayapp.site/birthday_app/controller/';
}

// エンティティ処理
function entity_str($str)
{
    return htmlspecialchars(
        $str,
        ENT_QUOTES,
        "UTF-8"
    );
}

function entity_array($array)
{
    foreach ($array as $key => $value) {
        foreach ($value as $keys => $values) {
            $array[$key][$keys] = entity_str($values);
        }
    }
    return $array;
}

// ユーザー名チェック(登録)
function user_name_check($link, $user_name)
{
    $duplication = false;
    if ($user_name === '') {
        return 'ユーザー名は半角英数字６文字以上、２０文字以内で入力してください';
    }
    if (preg_match('/^[a-zA-Z0-9]+$/', $user_name)) {
        $user_name_len = mb_strlen($user_name);
        if ($user_name_len < 6 || $user_name_len > 20) {
            return 'ユーザー名は半角英数字６文字以上、２０文字以内で入力してください';
        }
        // ユーザー名の重複チェック関数↓
        $duplication = duplication_check($link, $user_name);
        if ($duplication === true) {
            return '既に登録されているユーザー名です。ユーザー名を変更して下さい。';
        }
    } else {
        return 'ユーザー名は半角英数字６文字以上、２０文字以内で入力してください';
    }
}
// パスワードチェック(登録)
function password_check($password)
{
    if ($password === '') {
        return 'パスワードは半角英数字8文字以上、２０文字以内で入力してください';
    }
    if (preg_match('/^[a-zA-Z0-9]+$/', $password)) {
        $password_len = mb_strlen($password);
        if ($password_len < 8 || $password_len > 20) {
            return 'パスワードは半角英数字8文字以上、２０文字以内で入力してください';
        }
    } else {
        return 'パスワードは半角英数字8文字以上、２０文字以内で入力してください';
    }
}

// ファイルチェック
function file_check($temp_file, $file_name, &$ext)
{
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    if ($ext !== 'png' && $ext !== 'jpg' && $ext !== 'jpeg') {
        return '選択できるファイル形式は「PNG」、「JPEG」 です';
    }
}

// 名前チェック
function name_check(&$name)
{
    $name = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $name);
    if ($name === '') {
        return '名前を入力してください';
    }
    if (mb_strlen($name) > 20) {
        return '名前は20文字以内で入力してください';
    }
}

// 月日チェック
function birthday_check($month, $day)
{
    if ($month === 0 || $day === 0) {
        return '生年月日の月、日は入力必須です';
    }
}

// コメントチェック
function comment_check(&$comment)
{
    $comment = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $comment);
    if (mb_strlen($comment) > 30) {
        return 'コメントは30文字以内で入力してください';
    }
}
