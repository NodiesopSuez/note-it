<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
 require_once(dirname(__FILE__, 2).'/config/Config.php');
 require_once(dirname(__FILE__, 2).'/util/Utility.php');
 require_once(dirname(__FILE__, 2).'/config/Connect.php');
 require_once(dirname(__FILE__, 2).'/models/Users.php');
 require_once(dirname(__FILE__, 2).'/models/Searches.php');
 require_once(dirname(__FILE__, 2).'/models/Updates.php');

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
    
    header('Location:../mem/user_top.php'); 
    exit;
    
}catch(Exception $e){
    $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    header('Location:../mem/user_top.php');
    exit;
}
?>