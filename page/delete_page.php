<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
 require_once(dirname(__FILE__, 2).'/class/config/Config.php');
 require_once(dirname(__FILE__, 2).'/class/config/Icons.php');
 require_once(dirname(__FILE__, 2).'/class/db/Connect.php');
 require_once(dirname(__FILE__, 2).'/class/db/Searches.php');
 require_once(dirname(__FILE__, 2).'/class/util/Utility.php');

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
    $search  = new Searches;
    $utility = new SaftyUtil;

    //ページとコンテンツ情報
    $get_page_info = $search->findPageContentsA($page_id);
    $page_info     = $utility->sanitize(2, $get_page_info);
    //チャプター情報
    $chapter_id   = $page_info['chapter_id'];
    $chapter_info = $search->findChapterInfo('chapter_id', $chapter_id);
    //ノート情報
    $note_id   = $chapter_info[$chapter_id]['note_id'];
    $note_info = $search->findNoteInfo('note_id', $note_id);

    $note_title = $utility->sanitize(4, $note_info[$note_id]['note_title']);
    $color      = $utility->sanitize(4, $note_info[$note_id]['color']);
    $chapter_title = $utility->sanitize(4, $chapter_info[$chapter_id]['chapter_title']);

    $search = null;

}catch(Exception $e){
    $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    header('Location:../mem/mem_top.php');
    exit;
}

?>
