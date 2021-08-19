<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

authenticateError();
validToken();

//必要ファイル呼び出し
require_once(dirname(__FILE__, 3). '/config/Connect.php');
require_once(dirname(__FILE__, 3). '/models/Searches.php');

//エラーが入ってたら削除
$_SESSION['msg'] = array();

$user_id   = $_SESSION['user_info']['user_id'];
extract($_POST); //[token, note_id, color, note_title];

try {
    $search = new Searches;

    if (!$color) {
        $_SESSION['msg'] = ['error' => ['カラーを選択してください。']];
    }
    
    if ((!$note_title) || (ctype_space($note_title))) {
        $_SESSION['msg'] = ['error' => ['ノートのタイトルを入力してください。']];
    }

    $the_other_note = $search->findOtherNoteInfo($user_id, 'note_id', $note_id);

    foreach($the_other_note as $key => $val){
        if($note_title ===  $val['note_title'] && $color === $val['color']){
            $_SESSION['msg'] = ['error' => ['既に同じタイトル・カラーのノートがあります。']];
        }
    }

    $search = null;

    if(!empty($_SESSION['msg']['error'])){
        header('Location:/views/user/user_top.php'); //エラーがあったら入力ページに戻る
        exit;
    }else{
        $_SESSION['note_chapter'] = ['note_id' => $note_id, 'color' => $color, 'note_title' => $note_title];
        header('Location:/controllers/note/edit_note_done_controller.php');
        exit;
    }
}catch(Exception $e){
    catchException();
}
?>
