<?php 
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/util/Utility.php');
require_once('../class/db/Connect.php');
require_once('../class/db/Users.php');
require_once('../class/db/Searches.php');

//余計な情報を削除
$_SESSION['error'] = array();
$_SESSION['view_page'] = array();

//ログインしてなければログイン画面に
if(empty($_SESSION['user_info'])){
    header('Location: ../sign/sign_in.php');
    exit;
}

//ワンタイムトークンチェック
if(!SaftyUtil::validToken($_SESSION['token'])){
	$_SESSION['error'][] = Config::MSG_INVALID_PROCESS;
	header('Location: ../sign/sign_in.php');
	exit;
}

//user_idからノート情報検索
extract($_SESSION['user_info']);
$search = new Searches;
$search->findNoteInfo($user_id);
$search = null;

//現在の日本時刻を取得 >> 変数に分割
date_default_timezone_set('Asia/Tokyo');
$now_dt = getDate();
extract($now_dt);

if (empty($_SESSION['error']) && empty($_SESSION['okmsg'])) {
	$ladybug_img = './img/ladubug_nm.php';
    if ($hours>=5 && $hours<12) {
		$msg = array('おはようございます!　'.$nick_name.'さん!');
    } elseif ($hours>=12 && $hours<17) {
        $msg = array('こんにちは!　'.$nick_name.'さん!');
    } elseif (($hours>=17 && $hours<=23) || ($hours>=0 && $hours<5)) {
        $msg = array('こんばんは!　'.$nick_name.'さん!');
    }
}elseif(!empty($_SESSION['error'])){
	$ladybug_img = './img/ladybug_sd.png';
	$msg[] = $_SESSION['error'];
}elseif(!empty($_SESSION['okmsg'])){
	$ladybug_img = './img/ladubug_nm.php';
	$msg[] = $_SESSION['okmsg'];
	$_SESSION['okmsg'] = array();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include('../head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="./mem_top.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../inclusion/mem_header.css" media="screen and (min-width:1024px)">
</head>
<body>
    <div class="container">
    <?php include('../inclusion/mem_header.php')?>
    </div>
</body>
</html>