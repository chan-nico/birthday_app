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
    <link rel="stylesheet" href="../style/add_page.css">
    <script
        src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
        crossorigin="anonymous">
    </script>
    <script>
        var choice_year = JSON.parse('<?php echo $json_year; ?>');
        var choice_month = JSON.parse('<?php echo $json_month; ?>');
        var choice_date = JSON.parse('<?php echo $json_date; ?>');
    </script>
    <script type="text/javascript" src="../js/edit.js"></script>
</head>
<body>
    <header>
        <?php include_once '../view/header.php'; ?>
    </header>
    <section>
        <p class="under_bar">
            リスト追加ページ
        </p>
        <?php if ($suc_msg !== '') { ?>
            <p><?php echo $suc_msg; ?></p>
        <?php } ?>
        <?php foreach ($err_msg as $value) { ?>
            <p><?php echo $value; ?></p>
        <?php } ?>
        <form method="post" action="add.php" enctype="multipart/form-data">
            <div class="img_box">
                <img id="preview" src="../images/user_icon.png" style="max-width:250px;">
                <p>写真を追加</p>
                <input type="file" name="image" accept=".png, .jpg, .jpeg" onchange="previewImage(this);">
            </div>

            <div class="name_box">
                <p>名前（ 20文字以内 ）</p>
                <input type="text" name="name" placeholder="名前を入力" value="<?php echo $name; ?>"> *
            </div>

            <div class="birthday_box">
                <p>生年月日</p>
                <select id="year" name="year">
                    <option value="0">----</option>
                </select> 年　
                <select id="month" name="month">
                    <option value="0">--</option>
                </select> 月 *　
                <select id="date" name="day">
                    <option value="0">--</option>
                </select> 日 *　
            </div>
            <div class="comment_box">
                <p>一言コメント（ 30文字以内 ）</p>
                <input type="text" name="comment" placeholder="コメントを入力" value="<?php echo $comment; ?>">
            </div>
            <div class="button">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="submit" value="登録">
            </div>
        </form>
    </section>
</body>
</html>