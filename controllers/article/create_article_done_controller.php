<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

authenticateError();

//必要ファイル呼び出し
require_once(dirname(__FILE__, 3).'/config/Connect.php');
require_once(dirname(__FILE__, 3).'/models/Users.php');
require_once(dirname(__FILE__, 3).'/models/Additions.php');

//ユーザー情報
$user_id = $_SESSION['user_info']['user_id'];

try{
    //ノート・チャプター情報
    $register_info = $_SESSION['page']['register_info'];
    extract($register_info);

    //ページの内容
    $register_contents = $_SESSION['page']['register_contents'];
    extract($register_contents);

    $addition = new Addition;

    //現在日時
    $dt = new DateTime();
    $jpn = $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
    $addition_dt = $jpn->format('Y-m-d H:i:s');

    //note,chapter新規の場合→情報を追加
    if($note_existence === 'new'){
        $note_id    = $addition->createNewNote($note_title, $user_id, $note_color);
        $chapter_id = $addition->createNewChapter($chapter_title, $page_type, $note_id);
    }elseif($note_existence === 'exist' && $chapter_existence === 'new'){ 
        $chapter_id = $addition->createNewChapter($chapter_title, $page_type, $note_id);
    }
    $page_id = $addition->createNewPage($page_title, $addition_dt, $chapter_id);
    
    if($page_type === '1'){
        $register_contents_done = $addition->registerContentsA(
            $meaning, $syntax, $syn_memo, $example, $ex_memo, $memo, $page_id);
    }elseif($page_type === '2'){
        $register_contents_done = $addition->registerContentsB(
            $page_id, $register_contents
        );
    } 
    
    if($register_contents_done === false){
        catchException();
    }elseif($register_contents_done === true){
        $_SESSION['msg'] = ['okmsg' => ['新しいページを追加できました!']];
        header('Location:/views/user/user_top.php');
        exit;
    }

}catch(Exception $e){
    catchException();
}

?>
