<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
 require_once(dirname(__FILE__, 2).'/config/Config.php');
 require_once(dirname(__FILE__, 2).'/class/config/Icons.php');
 require_once(dirname(__FILE__, 2).'/class/db/Connect.php');
 require_once(dirname(__FILE__, 2).'/class/db/Searches.php');
 require_once(dirname(__FILE__, 2).'/util/Utility.php');

//ログインしてなければログイン画面に
if(empty($_SESSION['user_info'])){
    header('Location: ../sign/sign_in.php');
    exit;
}

//ワンタイムトークンチェック
if(!SaftyUtil::validToken($_SESSION['token'])){
	$_SESSION['msg'] = ['error' => [Config::MSG_INVALID_PROCESS]];
	header('Location: ../sign/sign_in.php');
	exit;
}

if(!empty($_SESSION['page'])){
    extract($_SESSION['page']);
}else{
    $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    header('Location: ../sign/sign_in.php');
    exit;
}

try {
    print_r($_SESSION['page']);

    
}catch(Exception $e){
    $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    header('Location:../mem/user_top.php');
    exit;
}

?>
