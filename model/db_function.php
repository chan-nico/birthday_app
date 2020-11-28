<?php
// DB接続
function db_connect()
{
    if (!$link = new PDO(
        'mysql:host=' . HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        USER,
        PASS
    )) {
        die('エラーが発生しました');
    }
    $link -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $link -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $link;
}

// DB切断
function db_close($link)
{
    $link = null;
}

// ユーザー登録
function insert_user($link, $user_name, $password)
{
    try {
        $date = date('Y-m-d H:i:s');
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO user_table(user_name, password, created_at,updated_at) VALUES (?, ?, ?, ?)';
        $stmt = $link -> prepare($sql);
        $stmt -> bindvalue(1, $user_name, PDO::PARAM_STR);
        $stmt -> bindvalue(2, $hash, PDO::PARAM_STR);
        $stmt -> bindvalue(3, $date, PDO::PARAM_STR);
        $stmt -> bindvalue(4, $date, PDO::PARAM_STR);
        $stmt -> execute();
        return true;
    } catch (exception $e) {
        return false;
    }
}

// ユーザー名の重複チェック
function duplication_check($link, $user_name)
{
    $sql = 'SELECT user_name FROM user_table WHERE user_name = ?';
    $stmt = $link -> prepare($sql);
    $stmt -> bindvalue(1, $user_name, PDO::PARAM_STR);
    $stmt -> execute();
    $result = $stmt -> fetch();
    if (isset($result['user_name'])) {
        return true;
    }
}

// ユーザーID取得
function get_user_id($link, $user_name, $password)
{
    $sql = 'SELECT user_id,password FROM user_table WHERE user_name = ?';
    $stmt = $link -> prepare($sql);
    $stmt -> bindvalue(1, $user_name, PDO::PARAM_STR);
    $stmt -> execute();
    $result = $stmt -> fetch();
    if (password_verify($password, $result['password'])) {
        return $result;
    }
}

// ユーザー名取得
function get_user_name($link, $user_id)
{
    $sql = 'SELECT user_name FROM user_table WHERE user_id = ?';
    $stmt = $link -> prepare($sql);
    $stmt -> bindvalue(1, $user_id, PDO::PARAM_INT);
    $stmt -> execute();
    $result = $stmt -> fetch();
    $user_name = $result['user_name'];
    return $user_name;
}

// リスト取得（特定の月）
function get_list($link, $user_id, $select_month)
{
    $sql = 'SELECT list_id,name,year,month,day,comment,img_name FROM list_table WHERE user_id = ? AND month = ?';
    $stmt = $link -> prepare($sql);
    $stmt -> bindvalue(1, $user_id, PDO::PARAM_INT);
    $stmt -> bindvalue(2, $select_month, PDO::PARAM_INT);
    $stmt -> execute();
    while ($result = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $list_array[] = $result;
    }
    return $list_array;
}

// リスト取得（全部）
function get_list_all($link, $user_id)
{
    $sql = 'SELECT list_id,name,year,month,day,comment,img_name FROM list_table
        WHERE user_id = ? ORDER BY month,day';
    $stmt = $link -> prepare($sql);
    $stmt -> bindvalue(1, $user_id, PDO::PARAM_INT);
    $stmt -> execute();
    while ($result = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $list_array[] = $result;
    }
    return $list_array;
}

// 一人分のリスト取得
function get_list_data($link, $user_id, $list_id)
{
    $sql = 'SELECT name,year,month,day,comment,img_name FROM list_table WHERE user_id = ? AND list_id = ?';
    $stmt = $link -> prepare($sql);
    $stmt -> bindvalue(1, $user_id, PDO::PARAM_INT);
    $stmt -> bindvalue(2, $list_id, PDO::PARAM_INT);
    $stmt -> execute();
    $result = $stmt -> fetch();
    return $result;
}

// リスト追加
function add_list($link, $user_id, $name, $year, $month, $day, $comment, $temp_file, $ext)
{
    try {
        $link -> beginTransaction();
        $date = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO list_table(user_id,name,year,month,day,comment,created_at,updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $link -> prepare($sql);
        $stmt -> bindvalue(1, $user_id, PDO::PARAM_INT);
        $stmt -> bindvalue(2, $name, PDO::PARAM_STR);
        $stmt -> bindvalue(3, $year, PDO::PARAM_INT);
        $stmt -> bindvalue(4, $month, PDO::PARAM_INT);
        $stmt -> bindvalue(5, $day, PDO::PARAM_INT);
        $stmt -> bindvalue(6, $comment, PDO::PARAM_STR);
        $stmt -> bindvalue(7, $date, PDO::PARAM_STR);
        $stmt -> bindvalue(8, $date, PDO::PARAM_STR);
        $stmt -> execute();
        if ($temp_file !== '' && $ext !== '') {
            $list_id = $link -> lastInsertId();
            $img_name = uniqid($list_id) . '.' . $ext;
            $sql = 'UPDATE list_table SET img_name = ? WHERE list_id = ? AND user_id = ?';
            $stmt = $link -> prepare($sql);
            $stmt -> bindvalue(1, $img_name, PDO::PARAM_STR);
            $stmt -> bindvalue(2, $list_id, PDO::PARAM_INT);
            $stmt -> bindvalue(3, $user_id, PDO::PARAM_INT);
            $stmt -> execute();

            if (move_uploaded_file($temp_file, IMAGE_DIR . $img_name)) {
                $link -> commit();
                return true;
            }
            $link -> rollback();
            return false;
        }

        $link -> commit();
        return true;
    } catch (exeption $e) {
        $link -> rollback();
        return false;
    }
}

// リスト削除
function delete_list($link, $user_id, $list_id)
{
    $img_name = null;
    try {
        $link -> beginTransaction(); // トランザクション
        // img_name取得
        $sql = 'SELECT img_name FROM list_table WHERE user_id = ? AND list_id = ?';
        $stmt = $link -> prepare($sql);
        $stmt -> bindvalue(1, $user_id, PDO::PARAM_INT);
        $stmt -> bindvalue(2, $list_id, PDO::PARAM_INT);
        $stmt -> execute();
        $result = $stmt -> fetch();
        $img_name = $result['img_name'];
        
        $sql = 'DELETE FROM list_table WHERE user_id = ? AND list_id = ?';
        $stmt = $link -> prepare($sql);
        $stmt -> bindvalue(1, $user_id, PDO::PARAM_INT);
        $stmt -> bindvalue(2, $list_id, PDO::PARAM_INT);
        $stmt -> execute();
        if ($img_name !== null) {
            if (unlink(IMAGE_DIR . $img_name)) {
                $link -> commit();
                return '削除に成功しました';
            } else {
                $link -> rollback();
                return '削除に失敗しました';
            }
        }
        $link -> commit();
        return '削除に成功しました';
    } catch (exception $e) {
        $link -> rollback();
        return '削除に失敗しました';
    }
}

//リスト編集
function edit_list($link, $user_id, $list_id, $name, $year, $month, $day, $comment, $temp_file, $ext)
{
    try {
        $link -> beginTransaction();
        $date = date('Y-m-d H:i:s');
        $sql = 'UPDATE list_table SET name = ?, year = ?, month = ?, day = ?, comment = ?, updated_at = ?
            WHERE user_id = ? AND list_id = ?';
        $stmt = $link -> prepare($sql);
        $stmt -> bindvalue(1, $name, PDO::PARAM_STR);
        $stmt -> bindvalue(2, $year, PDO::PARAM_INT);
        $stmt -> bindvalue(3, $month, PDO::PARAM_INT);
        $stmt -> bindvalue(4, $day, PDO::PARAM_INT);
        $stmt -> bindvalue(5, $comment, PDO::PARAM_STR);
        $stmt -> bindvalue(6, $date, PDO::PARAM_STR);
        $stmt -> bindvalue(7, $user_id, PDO::PARAM_INT);
        $stmt -> bindvalue(8, $list_id, PDO::PARAM_INT);
        $stmt -> execute();
        if ($temp_file !== '' && $ext !== '') {
            $sql = 'SELECT img_name FROM list_table WHERE user_id = ? AND list_id = ?';
            $stmt = $link -> prepare($sql);
            $stmt -> bindvalue(1, $user_id, PDO::PARAM_INT);
            $stmt -> bindvalue(2, $list_id, PDO::PARAM_INT);
            $stmt -> execute();
            $result = $stmt -> fetch();
            $img_name = $result['img_name'];
            unlink(IMAGE_DIR . $img_name);
    
            $img_name = uniqid($list_id) . '.' . $ext;
            $sql = 'UPDATE list_table SET img_name = ? WHERE list_id = ? AND user_id = ?';
            $stmt = $link -> prepare($sql);
            $stmt -> bindvalue(1, $img_name, PDO::PARAM_STR);
            $stmt -> bindvalue(2, $list_id, PDO::PARAM_INT);
            $stmt -> bindvalue(3, $user_id, PDO::PARAM_INT);
            $stmt -> execute();

            if (move_uploaded_file($temp_file, IMAGE_DIR . $img_name)) {
                $link -> commit();
                return true;
            }
            $link -> rollback();
            return false;
        }

        $link -> commit();
        return true;
    } catch (exeption $e) {
        $link -> rollback();
        return false;
    }
}
