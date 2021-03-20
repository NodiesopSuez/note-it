<?php
session_start();
session_regenerate_id();

//外部ファイル読込
require_once('../class/config/Config.php');

$msg = '情報を入力してください';

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include('../head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="./sign_up.css" media="screen and (min-width:1024px)">
</head>
<body>
    <div class="container">
        <div class="ladybug">
            <img src="./img/ladybug_nm.png">
            <div class="balloon">
                <?= $msg ?>
            </div>
        </div>
        <form method="post" action="sign_up_check.php">
            <!-- ワンタイムトークン発生 -->
            



            <div class="form-group text">
                <label>email</label>
                <input type="text" name="email" class="form-controll">
            </div>
            <div class="form-group text">
                <label>name</label>
                <input type="text" name="name" class="form-controll">
            </div>
            <div class="form-group gender">
                <label>gender</label>
                <ul>
                    <li><input type="radio" name="gender" value="male">男性</li>
                    <li><input type="radio" name="gender" value="female">女性</li>
                    <li><input type="radio" name="gender" value="other">その他</li>
                </ul>
            </div>
            <div class="form-group text">
                <label>birth</label><br/>
                <input type="date" name="birth" class="form-controll">
            </div>
            <div class="form-group text">
                <label>password</label><br/>
                <input type="password" name="pass" class="form-controll">
            </div>
            <div class="form-group text">
                <label>password(再度ご入力ください)</label><br/>
                <input type="password" name="pass2" class="form-controll">
            </div>

            <button type="submit" class="send">confirm</button>
        </form>
    </div>
</body>
</html>

