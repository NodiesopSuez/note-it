<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

authenticateError();
validToken();

//必要ファイル呼び出し
require_once(dirname(__FILE__, 2).'/config/Connect.php');
require_once(dirname(__FILE__, 2).'/models/Users.php');
require_once(dirname(__FILE__, 2).'/models/Searches.php');
require_once(dirname(__FILE__, 2).'/models/Deletes.php');

//メッセージ入ってたら削除
if(!empty($_SESSION['msg'])){
    $_SESSION['msg'] = array();
}

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
    $delete_bool['chapter'] = $delete->deleteChapter('chapter', $chapter_id);

    if(in_array(0, $delete_bool)){
        $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    }else{
        $_SESSION['msg'] = ['okmsg' => ['チャプターを削除できました！']];
    }
    
    header('Location:/views/user/user_top.php');
    exit;
    
}catch(Exception $e){
    catchException();
}

?>