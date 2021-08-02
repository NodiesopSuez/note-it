<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

authenticateError();
validToken();

//必要ファイル呼び出し
require_once(dirname(__FILE__, 2).'/config/Connect.php');
require_once(dirname(__FILE__, 2).'/models/Searches.php');

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
        header('Location:/views/user/user_top.php'); 
        exit;
    }else{
        $_SESSION['note_chapter'] = ['chapter_id' => $chapter_id, 'chapter_title' => $chapter_title];
        header('Location:/controllers/chapter/edit_chapter_done_controller.php');
        exit;
    }
}catch(Exception $e){
    catchException();
}
?>