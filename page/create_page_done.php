<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/util/Utility.php');
require_once('../class/db/Connect.php');
require_once('../class/db/Users.php');
require_once('../class/db/Additions.php');

//ログインしてなければログイン画面へ
if(empty($_SESSION['user_info'])){
    header('Location:../sign/sign_in.php');
}

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
        $_SESSION['error'][] = Config::MSG_EXCEPTION;
        header('Location:../page/create_page.php');
        exit;
    }elseif($register_contents_done === true){
        $_SESSION['okmsg'][] = '新しいページを追加できました！';
        header('Location:../mem/mem_top.php');
        exit;
    }

}catch(Exception $e){
    echo $e->getMessage();
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
    header('Location:../page/create_page.php');    
    exit;
}

?>
