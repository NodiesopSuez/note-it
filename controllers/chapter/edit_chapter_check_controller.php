<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
 require_once(dirname(__FILE__, 2).'/config/Config.php');
 require_once(dirname(__FILE__, 2).'/util/Utility.php');
 require_once(dirname(__FILE__, 2).'/config/Connect.php');
 require_once(dirname(__FILE__, 2).'/models/Searches.php');

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

//エラーが入ってたら削除
$_SESSION['msg'] = array();

$user_id = $_SESSION['user_info']['user_id'];
extract($_POST); //[token, chapter_id, chapter_title];

try {
    $search = new Searches;
    
    if ((!$chapter_title) || (ctype_space($chapter_title))) {
        $_SESSION['msg'] = ['error' => ['チャプタータイトルを入力してください。']];
    }

    //該当チャプター
    $applicable_chapter = $search->findChapterInfo('chapter_id', $chapter_id);
    //その他のチャプター情報
    $the_other_chapter = $search->findOtherChapterInfo($applicable_chapter[$chapter_id]['note_id'], $chapter_id);

    //既に同じタイトルチャプターがないか検索
    foreach($the_other_chapter as $key => $val){
        if($chapter_title ===  $val['chapter_title']){
            $_SESSION['msg'] = ['error' => ['既に同じタイトルのチャプターがあります。']];
        }
    } 

    $search = null;

    if(!empty($_SESSION['msg']['error'])){
        header('Location:../mem/user_top.php'); 
        exit;
    }else{
        $_SESSION['note_chapter'] = ['chapter_id' => $chapter_id, 'chapter_title' => $chapter_title];
        header('Location:../note_chapter/edit_chapter_done.php');
        exit;
    }
}catch(Exception $e){
    $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    header('Location:../mem/user_top.php');
    exit;
}
?>