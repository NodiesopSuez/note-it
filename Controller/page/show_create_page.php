<?php

include(dirname(__FILE__, 2).'/common/redirect.php');


//必要ファイル呼び出し
require_once(dirname(__FILE__, 2).'/class/db/Connect.php');
require_once(dirname(__FILE__, 2).'/class/db/Users.php');
require_once(dirname(__FILE__, 2).'/class/db/Searches.php');


$color = 'basic'; //ヘッダーメニューのカラークラス

//既存ノートリスト取得
$user_id = $_SESSION['user_info']['user_id'];
$searches = new Searches;
$note_list = $searches->findNoteInfo('user_id', $user_id);

//エラーの有無によってテントウの表示を分岐
if(!empty($_SESSION['msg']['error'])){
    $ladybug_img = './img/ladybug_sd.png';
    $msg = $_SESSION['msg']['error'];
}elseif(!empty($_SESSION['msg']['okmsg'])){
    $ladybug_img = './img/ladybug_nm.png';
    $msg = $_SESSION['msg']['okmsg'];
}else{
    $ladybug_img = './img/ladybug_nm.png';
    $msg = ['どのノートに追加しますか？'];
}
$_SESSION['msg'] = count($msg)>=2 ? implode("<br/>", $msg) : $msg[0];
$color_list = ['blue', 'pink', 'yellow', 'green', 'purple'];

$_SESSION['msg'] = array();

header('Location:../Views/page/create_page.php');
?>