<?php

include(dirname(__FILE__, 2).'/Controller/page/show_sign_in.php');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(dirname(__FILE__, 3).'/head.php')?>
    <link rel="stylesheet" type="text/css" href="../public/css/template.css">
    <link rel="stylesheet" type="text/css" href="../public/css/color_template.css">
    <link rel="stylesheet" type="text/css" href="../public/css/sign_up.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../public/css/top_header.css">
</head>
<body>
    <div class="container">
    <?php include(dirname(__FILE__, 2).'/Views/top_header.php')?>
        <div class="ladybug">
            <div class="balloon">
                <div class="msg">
                    <?= $show_msg ?>
                </div>
                <div class="tail"></div>
            </div>
            <img src="<?= $ladybug ?>">
        </div>
        <form method="post" action="../Controller/user/sign_in_check.php" class="basic">
            <!-- ワンタイムトークン発生 -->
            <input type="hidden" name="token" value="<?= SaftyUtil::generateToken()?>">
            <div class="form-group text">
                <label>email</label>
                <input type="text" name="email" class="form-controll">
            </div>
            <div class="form-group text">
                <label>password</label>
                <input type="password" name="pass" class="form-controll">
            </div>
            <button type="submit" class="submit">confirm</button>
        </form>
    </div>
    <!-- <script src="../inclusion/inclusion.js" type="text/javascript"></script> -->
</body>
