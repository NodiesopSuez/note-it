<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
 require_once(dirname(__FILE__, 2). '/class/config/Config.php');
 require_once(dirname(__FILE__, 2). '/class/util/Utility.php');
 require_once(dirname(__FILE__, 2). '/class/db/Connect.php');
 require_once(dirname(__FILE__, 2). '/class/db/Searches.php');

//ログインしてなければログイン画面に
if(empty($_SESSION['user_info'])){
    header('Location: ../sign/sign_in.php');
    exit;
}

//ワンタイムトークンチェック
if(!SaftyUtil::validToken($_SESSION['token'])){
	$_SESSION['msg']['error'][] = Config::MSG_INVALID_PROCESS;
	header('Location: ../sign/sign_in.php');
	exit;
}

//エラーが入ってたら削除
$_SESSION['msg']['error'] = array();

$user_id   = $_SESSION['user_info']['user_id'];
extract($_POST); //[token, note_id, color, note_title];

try {
    $search = new Searches;

    if (!$color) {
        $_SESSION['error'][] = 'カラーを選択してください';
    }
    
    if ((!$note_title) || (ctype_space($note_title))) {
        $_SESSION['error'][] = 'ノートタイトルを入力してください';
    }

    $the_other_note = $search->findOtherNoteInfo($user_id, 'note_id', $note_id);

    foreach($the_other_note as $key => $val){
        if($note_title ===  $val['note_title'] && $color === $val['color']){
            $_SESSION['msg']['error'][] = '既に同じタイトル・カラーのノートがあります';
        }
    }

    $search = null;

    if(!empty($_SESSION['msg']['error'])){
        var_dump($_SESSION['msg']['error']);
        header('Location:../mem/mem_top.php'); //エラーがあったら入力ページに戻る
        exit;
    }else{
        $_SESSION['note_chapter'] = ['note_id' => $note_id, 'color' => $color, 'note_title' => $note_title];
        header('Location:../note_chapter/edit_note_done.php');
        exit;
    }
}catch(Exception $e){
    echo $e->getMessage();
    $_SESSION['msg']['error'][] = Config::MSG_EXCEPTION;
    header('Location:../mem/mem_top.php');
    exit;
}
?>
