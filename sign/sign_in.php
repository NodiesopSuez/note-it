<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/util/Utility.php');

$msg = [];
$ladybug = './img/ladybug_nm.png';

//エラー戻りならばエラーメッセージと試行回数を代入
//会員登録後ならサンクスメッセージを代入
//それ以外は通常メッセージ
$error_back_count;
if(!empty($_SESSION['error'])){
    $ladybug = './img/ladybug_sd.png';
	$msg = $_SESSION['error'];
	$error_back_count = $_SESSION['error_back_count'];
}elseif($_SESSION['add_ok']==='ok'){
	$msg = ['登録を完了しました!!<br/>ログインしてください'];
}else{
    $msg = ['ログインしてください♪'];
}
$_SESSION['add_ok'] = array();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include('../head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="../main/color_template.css">
    <link rel="stylesheet" type="text/css" href="./sign_up.css" media="screen and (min-width:1024px)">
</head>
<body>
    <div class="container">
        <div class="ladybug">
            <img src="<?= $ladybug ?>">
            <div class="balloon">
                <?php foreach($msg as $m): ?>
                <?= $m ?><br/>
                <?php endforeach ?>
            </div>
        </div>
        <form method="post" action="sign_in_check.php">
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
            <button type="submit" class="send">confirm</button>
        </form>
    </div>
</body>
