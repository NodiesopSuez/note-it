<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
 require_once(dirname(__FILE__, 2).'/class/config/Config.php');
 require_once(dirname(__FILE__, 2).'/class/util/Utility.php');

$msg = [];
$ladybug = './img/ladybug_nm.png';

//エラー戻りならばエラーメッセージと試行回数を代入
//会員登録後ならサンクスメッセージを代入
//それ以外は通常メッセージ
$error_back_count;
if(!empty($_SESSION['msg']['error'])){
    $ladybug = './img/ladybug_sd.png';
	$msg = $_SESSION['msg']['error'];
	$error_back_count = $_SESSION['error_back_count'];
}elseif(isset($_SESSION['msg']['okmsg']) && $_SESSION['msg']['okmsg']==='ok'){
	$msg = $_SESSION['msg']['okmsg'];
}else{
    $msg = ['ログインしてください♪'];
}
$show_msg = count($msg)>=2 ? implode("<br/>", $msg) : $msg[0];
$_SESSION['msg']= array();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(dirname(__FILE__, 2).'/head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="../main/color_template.css">
    <link rel="stylesheet" type="text/css" href="./sign_up.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../inclusion/top_header.css">
</head>
<body>
    <div class="container">
    <?php include(dirname(__FILE__, 2).'/inclusion/top_header.php')?>
        <div class="ladybug">
            <div class="balloon">
                <div class="msg">
                    <?= $show_msg ?>
                </div>
                <div class="tail"></div>
            </div>
            <img src="<?= $ladybug ?>">
        </div>
        <form method="post" action="sign_in_check.php" class="basic">
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
