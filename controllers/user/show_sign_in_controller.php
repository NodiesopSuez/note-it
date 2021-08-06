<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

$msg = [];
$ladybug = '/public/img/ladybug_nm.png';

//エラー戻りならばエラーメッセージと試行回数を代入
//会員登録後ならサンクスメッセージを代入
//それ以外は通常メッセージ
$error_back_count;
if(!empty($_SESSION['msg']['error'])){
    $ladybug = '/public/img/ladybug_sd.png';
	$msg = $_SESSION['msg']['error'];
	$error_back_count = $_SESSION['error_back_count'];
}elseif(!empty($_SESSION['msg']['okmsg'])){
	$msg = $_SESSION['msg']['okmsg'];
}else{
    $msg = ['ログインしてください♪'];
}
$show_msg = count($msg)>=2 ? implode("<br/>", $msg) : $msg[0];
unset($_SESSION['msg']);

?>
