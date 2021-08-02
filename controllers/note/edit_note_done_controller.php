<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

authenticateError();
validToken();


//必要ファイル呼び出し
require_once(dirname(__FILE__, 3).'/config/Connect.php');
require_once(dirname(__FILE__, 3).'/models/Users.php');
require_once(dirname(__FILE__, 3).'/models/Searches.php');
require_once(dirname(__FILE__, 3).'/models/Updates.php');

//エラーが入ってたら削除
$_SESSION['msg'] = array();

$user_id = $_SESSION['user_info']['user_id'];
extract($_SESSION['note_chapter']); //[note_id, color, note_title];

try {
    $update = new Updates;
    $update_bool = $update->updateNote($note_id, $color, $note_title);

    if($update_bool === false){
        $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    }else{
        $_SESSION['msg'] = ['okmsg' => ['ノートの更新ができました!']];
        unset($_SESSION['note_chapter']);
    }
    
    $update = null;
    
    header('Location:/views/user/user_top.php'); 
    exit;
    
}catch(Exception $e){
   catchException();
}
?>
