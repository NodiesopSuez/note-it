<?php 
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/util/Utility.php');
require_once('../class/db/Connect.php');
require_once('../class/db/Users.php');
require_once('../class/db/Updates.php');

//ログインしてなければログイン画面へ
/* if(empty($_SESSION['user_data'])){
    header('Location:../sign/sign_in.php');
} */


try{
    //ページの内容
    $update_contents = $_SESSION['page']['update_contents'];

    var_dump($update_contents);

    $update = new Updates;

    if($update_contents['page_type'] === 1){
        $update_contents_done = $update->updatePageContentsA($update_contents);
        echo $update_contents_done;
    }else{
       
    }
}catch(Exception $e){
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
    header('Location:../mem/mem_top.php');
    exit;
}

?>
