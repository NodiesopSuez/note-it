<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/util/Utility.php');
require_once('../class/db/Connect.php');
require_once('../class/db/Searches.php');

/* //ログインしてなければログイン画面に
if(empty($_SESSION['user_info'])){
    header('Location: ../sign/sign_in.php');
    exit;
}

//ワンタイムトークンチェック
if(!SaftyUtil::validToken($_SESSION['token'])){
	$_SESSION['error'][] = Config::MSG_INVALID_PROCESS;
	header('Location: ../sign/sign_in.php');
	exit;
} */

//エラーが入ってたら削除
$_SESSION['error'] = array();

$user_id   = 4; //$_SESSION['user_info']['user_id'];
extract($_POST); //[token, chapter_id, chapter_title];

try {
    $search = new Searches;
    
    if ((!$chapter_title) || (ctype_space($chapter_title))) {
        $_SESSION['error'][] = 'チャプタータイトルを入力してください';
    }

    //該当チャプター
    $applicable_chapter = $search->findChapterInfo('chapter_id', $chapter_id);
    //その他のチャプター情報
    $the_other_chapter = $search->findOtherChapterInfo($applicable_chapter[$chapter_id]['note_id'], $chapter_id);

    //既に同じタイトルチャプターがないか検索
    foreach($the_other_chapter as $key => $val){
        if($chapter_title ===  $val['chapter_title']){
            $_SESSION['error'][] = '既に同じタイトルのチャプターがあります';
        }
    } 

    $search = null;

    if(!empty($_SESSION['error'])){
        header('Location:../mem/mem_top.php'); 
        exit;
    }else{
        $_SESSION['note_chapter'] = ['chapter_id' => $chapter_id, 'chapter_title' => $chapter_title];
        header('Location:../note_chapter/edit_chapter_done.php');
        exit;
    }
}catch(Exception $e){
    echo $e->getMessage();
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
    header('Location:../mem/mem_top.php');
    exit;
}
?>
