<?php
  // クリックジャッキング対策
  header('X-FRAME-OPTIONS: DENY');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>誕生日手帳</title>
    <link rel="stylesheet" href="../style/header.css">
    <link rel="stylesheet" href="../style/main_page.css">
    <script
        src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
        crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="../js/edit.js"></script>
</head>
<body>
    <header>
        <?php include_once '../view/header.php'; ?>
    </header>
    <section>
        <div class="month_display">
            <p class="red">
                <?php if (isset($result_msg)) {
                    echo $result_msg;
                } ?>
            </p>
            <p class="under_bar">
                <?php if ($select_month === '') {
                    echo '誕生日リスト';
                } else {
                    echo $select_month . '月の誕生日リスト';
                } ?>
            </p>
        </div>
        <?php foreach ($list_array as $value) { ?>
            <div class="list_box">
                <div class="img_box">
                    <img src="../images/<?php
                    if ($value['img_name'] === '') {
                        echo 'user_icon.png';
                    } else {
                        echo $value['img_name'];
                    }?>">
                </div>
                <div class="contents_box">
                    <div class="list_data_box">
                        <p class="name"><?php echo $value['name']; ?></p>
                        <div class="birthday">
                            <p class="year">
                                <?php if ($value['year'] === '0') {
                                    echo '---------';
                                } else {
                                    echo $value['year'] . '年';
                                } ?>
                            </p>
                            <p class="month"><?php echo $value['month'] . '月'; ?></p>
                            <p class="day"><?php echo $value['day'] . '日'; ?></p>
                        </div>
                    </div>
                    <p class="comment">
                        <?php if (isset($value['comment'])) {
                            echo $value['comment'];
                        } ?>
                    </p>
                </div>
                <div class="submit_box">
                    <form action="edit.php" method="post" class="edit_button">
                        <input type="hidden" name="list_id" value="<?php echo $value['list_id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="submit" class="button" value="編集">
                    </form>
                    <form action="main.php" method="post" class="delete_button">
                        <input type="hidden" name="list_id" value="<?php echo $value['list_id']; ?>">
                        <input type="hidden" name="delete_flag" value='true'>
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        <input type="submit" class="button" value="削除" onclick="return confirm('削除します。よろしいですか？')">
                    </form>
                </div>
            </div>
            <hr>
        <?php } ?>
    </section>
</body>
</html>
