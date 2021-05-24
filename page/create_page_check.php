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

print_r($_POST);
print_r($_FILES);

//$user_id = $_SESSION['user_data']['user_id'];
$user_id = 4;

//ワンタイムトークンチェック
if(!SaftyUtil::validToken($_SESSION['token'])){
    $_SESSION['error'][] = Config::MSG_INVALID_PROCESS;
    header('Location:../mem/mem_top.php');
    exit;
}

//エラー・前回の入力残ってたら削除
if(!empty($_SESSION['error'])){
    $_SESSION['error']       = array();
    $_SESSION['create_page'] = array();
}

try {
    extract($_POST); //POSTで受け取った配列を変数にする

    $search = new Searches;

    //新規ノート作成の場合
    if($note_existence === 'new'){
        $note_list = $search->findNoteInfo('user_id', $user_id);

        if (empty($new_note_title) || ctype_space($new_note_title)) {
            $_SESSION['error'][] = 'ノートのタイトルを入力してください';
        }
        if (in_array($new_note_title, $note_list)) {
            $_SESSION['error'][] = '既にそのノートは作成されています';
        }
        if (empty($note_color)){
            $_SESSION['error'][] = 'ノートのカラーを選択してください';
        }
    }

    //既存ノートに作成する場合
    if($note_existence === 'exist'){
        if(!isset($note_id) || $note_id = ''){
            $_SESSION['error'][] = 'ノートのタイトルを選択してください';    
        }else{
            //チャプターリストを取得しておく
            $chapter_list = $search->findChapterInfo($note_id);
        }
    }
    
    //新規チャプター作成の場合
    if($chapter_existence === 'new'){   
        if ($page_type !== 1 && $page_type !== 2) {
            $_SESSION['error'][] = 'ページのタイプを選択してください';
        }
        if (empty($new_chapter_title) || ctype_space($new_chapter_title)) {
            $_SESSION['error'][] = 'チャプターのタイトルを入力してください';
        }
        if (in_array($new_chapter_title, $chapter_list)){
            $_SESSION['error'][] = '既にそのチャプターは作成されています';
        }
    }

    //既存チャプターに作成する場合
    if(($chapter_existence === 'exist') && (!isset($chapter_id) || $chapter_id === '')){
        $_SESSION['error'][] = 'チャプターを選択してください';
    }

    //page_titleが入力されているか
    if(empty($page_title) || ctype_space($page_title)){
        $_SESSION['error'][''] = 'ページタイトルを入力してください';
    }
    
print_r($_POST);


     

}catch(Exception $e){
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
    header('Location:../page/create_page.php');
    exit;
}

?>