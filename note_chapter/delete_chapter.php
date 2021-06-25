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
require_once('../class/db/Deletes.php');

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
if(!empty($_SESSIONT['error'])){
    $_SESSION['error'] = array();
}

//extract($_SESSION['user_info']);
var_dump($_POST);
$chapter_id = $_POST['chapter_id'];

try{
    $search = new Searches;
    $delete = new Deletes;

    $chapter_info = $search->findChapterInfo('chapter_id', $chapter_id); //チャプター情報

    //chapter_idをString型にしてページとコンテンツ削除
    if($chapter_info[$chapter_id]['page_type'] === 1){
        $delete_bool['page_a'] = $delete->deletePageContents('note_chapter', 1, $chapter_id);
    }elseif($chapter_info[$chapter_id]['page_type'] === 2){
        $delete_bool['page_b_file'] = $delete->removeFiles('note_chapter', $chapter_id);  //サーバ上の画像ファイル削除
        $delete_bool['page_b']      = $delete->deletePageContents('note_chapter', 2, $chapter_id);
    }

    //チャプターを削除
    $delete_bool['chatper'] = $delete->deleteChapter('chapter', $chapter_id);

    if(in_array(0, $delete_bool)){
        $_SESSION['error'][] = Config::MSG_EXCEPTION;
        header('Location:../mem/mem_top.php');
        exit;
    }else{
        $_SESSION['okmsg'][] = 'チャプターを削除できました！';
        header('Location:../mem/mem_top.php');
        exit;
    }

}catch(Exception $e){
    echo $e->getMessage();
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
    header('Location:../mem/mem_top.php');
    exit;
}

?>