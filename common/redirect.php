<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once(dirname(__FILE__, 2).'/config/Config.php');
require_once(dirname(__FILE__, 2).'/util/Utility.php');
require_once(dirname(__FILE__, 2).'/config/Icons.php');

//ログインしてなければログイン画面に
function cannnotAuthenticate(){
    if (empty($_SESSION['user_info'])) {
        header('Location: ../Views/user/sign_in.php');
        exit;
    }
}

//ワンタイムトークンチェック
function validToken(){
    if(!SaftyUtil::validToken($_SESSION['token'])){
        $_SESSION['msg'] = ['error' => [Config::MSG_INVALID_PROCESS]];
        header('Location: ../Views/user/sign_in.php');
        exit;
    }
}

function catchException(){
	$_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    header('Location:../Views/user/user_top.php');
    exit;
}

?>