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
    <link href="../style/login_page.css" rel="stylesheet">
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
            <form action="login.php" method="post" class="form-signin">
                <h2 class="form-signin-heading">誕生日手帳</h2>
                <hr class="colorbar">
                <?php if ($login_err_flag === true) {
                    if (!empty($err_msg)) {
                        foreach ($err_msg as $value) { ?>
                            <p><?php echo $value; ?></p>
                        <?php }
                    } else { ?>
                        <p>ユーザー名またはパスワードが違います</p>
                    <?php }
                } ?>

                <input type="text" class="form-control" name="user_name" placeholder="ユーザー名" required="" autofocus="">
                <input type="password" class="form-control" name="password" placeholder="パスワード" required="">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <button class="btn btn-lg btn-primary btn-block" name="submit" value="ログイン" type="submit">ログイン</button>
                <a href="signup.php">ユーザー新規登録</a> 
            </form>
        </div>
    </div>
</body>
</html>
