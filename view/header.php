<div class="header_box">
    <div class="header_title">
        <h1>誕生日手帳</h1>
    </div>
    <div class="header_cont">
        <div class="cont_1">
            <img src="../images/user_icon_white.png" alt="">
            <p><?php echo $user_name; ?></p>
        </div>
        <div class="cont_2">
            <a href="main.php">
                <img src="../images/home_white.png" alt="">
            </a>
            <p>ホーム</p>
        </div>
        <div class="cont_3">
            <a href="add.php">
                <img src="../images/edit_white.png" alt="">
            </a>
            <p>リスト追加</p>
        </div>
        <div class="cont_4">
            <a href="logout.php">
                <img src="../images/logout_white.png" alt="">
            </a>
            <p>ログアウト</p>
        </div>
        <div class="cont_5">
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <nav class="globalMenuSp">
                <ul>     
                    <li>
                        <?php foreach ($month_list as $value) { ?>
                            <form action="main.php" method="post" name="form<?php echo $value; ?>">
                                <input type="hidden" name="select_month" value="<?php echo $value; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <a href="javascript:form<?php echo $value; ?>.submit()">
                                <?php echo $value; ?>月</a> 
                            </form>
                        <?php } ?>
                    </li>
                </ul>
            </nav>
            <p>表示月</p>
        </div>
    </div>
</div>
