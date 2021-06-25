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
require_once('../class/db/Updates.php');

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

//エラーが入ってたら削除
$_SESSION['error'] = array();

$user_id = $_SESSION['user_info']['user_id'];
extract($_SESSION['note_chapter']); //[chapter_id, chapter_title];
var_dump($chapter_id);

try {
    $update = new Updates;
    $update_bool = $update->updateChapter($chapter_id, $chapter_title);

    if($update_bool === false){
        $_SESSION['error'][] = Config::MSG_EXCEPTION;
        header('Location:../mem/mem_top.php'); //エラーがあったら入力ページに戻る
        exit;
    }else{
        $_SESSION['okmsg'][] = 'チャプターの更新ができました！';
        $_SESSION['note_chapter'] = array();
        header('Location:../mem/mem_top.php'); 
        exit;
    }

    $update = null;

}catch(Exception $e){
    echo $e->getMessage();
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
    header('Location:../mem/mem_top.php');
    exit;
}
?>
