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
    <link href="../style/signup_page.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script
        src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8="
        crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
    </script>
</head>
<body>
    <div class="container">
        <div class="wrapper">
            <form action="signup.php" method="post" class="form-signin">
                <h2 class="form-signin-heading">誕生日手帳</h2>
                <hr class="colorbar">
                <?php if ($suc_msg === true) { ?>
                    <p>新規ユーザー登録に成功しました</p>
                <?php } ?>
                
                <?php if (!empty($err_msg)) {
                    foreach ($err_msg as $value) { ?>
                        <p><?php echo $value; ?></p>
                    <?php }
                } ?>

                <input type="text" class="form-control" name="user_name" placeholder="ユーザー名" required="" autofocus="">
                <input type="password" class="form-control" name="password" placeholder="パスワード" required="">
                <button class="btn btn-lg btn-primary btn-block" name="submit" value="新規登録" type="submit">新規登録</button>
                <a href="login.php">ログインページ</a>
            </form>
        </div>
    </div>
</body>
</html>
