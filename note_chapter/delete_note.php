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
if(!empty($_SESSIONT['error'])){
    $_SESSION['error'] = array();
}

//extract($_SESSION['user_info']);
$note_id = $_POST['note_id'];

try{
    $search = new Searches;
    $delete = new Deletes;

    $chapter_list = $search->findChapterInfo($note_id); //チャプターリスト
    var_dump($chapter_list);
    $page_a = array();
    $page_b = array();
    foreach($chapter_list as $chapter_id => $val){
        $val['page_type'] == 1 ? $page_a[] = $chapter_id : null; //typeAのチャプター
        $val['page_type'] == 2 ? $page_b[] = $chapter_id : null; //typeBのチャプター
    }

    //chapter_idをString型にしてページとコンテンツ削除
    if(!empty($page_a)){
        if(count($page_a) > 1){
            $page_a_str = implode(',', $page_a);
        }elseif(count($page_a) === 1){
            $page_a_str = implode($page_a);
        }
        $delete_bool['page_a'] = $delete->deletePageContents('note_chapter', 1, $page_a_str);
    }

    if(!empty($page_b)){
        if(count($page_b) > 1){
            $page_b_str = implode(',', $page_b);
        }elseif(count($page_b) === 1){
            $page_b_str = implode($page_b);
        }
        $delete_bool['page_b_file'] = $delete->removeFiles('note_chapter', $page_b_str);  //サーバ上の画像ファイル削除
        $delete_bool['page_b']      = $delete->deletePageContents('note_chapter', 2, $page_b_str);
    }

    //チャプターを削除
    $delete_bool['chapter'] = $delete->deleteChapter('note', $note_id);
    
    //ノートを削除
    $delete_bool['note'] = $delete->deleteNote($note_id);

    if(in_array(0, $delete_bool)){
        $_SESSION['error'][] = Config::MSG_EXCEPTION;
        header('Location:../mem/mem_top.php');
        exit;
    }else{
        $_SESSION['okmsg'][] = 'ノートを削除できました！';
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