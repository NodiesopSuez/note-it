<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

authenticateError();
validToken();

//必要ファイル呼び出し
require_once(dirname(__FILE__, 3).'/config/Connect.php');
require_once(dirname(__FILE__, 3).'/models/Users.php');
require_once(dirname(__FILE__, 3).'/models/Searches.php');

//既存ノートリスト取得
$user_id = $_SESSION['user_info']['user_id'];
$searches = new Searches;
$note_list = $searches->findNoteInfo('user_id', $user_id);

//エラーの有無によってテントウの表示を分岐
if(!empty($_SESSION['msg']['error'])){
    $_SESSION['ladybug_img'] = '/public/public/img/ladybug_sd.png';
    $msg = $_SESSION['msg']['error'];
}elseif(!empty($_SESSION['msg']['okmsg'])){
    $_SESSION['ladybug_img'] = '/public/public/img/ladybug_nm.png';
    $msg = $_SESSION['msg']['okmsg'];
}else{
    $_SESSION['ladybug_img'] = '/public/public/img/ladybug_nm.png';
    $msg = ['どのノートに追加しますか？'];
}
$msg = count($msg)>=2 ? implode("<br/>", $msg) : $msg[0];

$color = 'basic'; //ヘッダーメニューのカラークラス
$color_list = ['blue', 'pink', 'yellow', 'green', 'purple'];
$_SESSION['msg'] = array();

?>