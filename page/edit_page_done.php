<?php 
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
 require_once(dirname(__FILE__, 2).'/class/config/Config.php');
 require_once(dirname(__FILE__, 2).'/class/util/Utility.php');
 require_once(dirname(__FILE__, 2).'/class/db/Connect.php');
 require_once(dirname(__FILE__, 2).'/class/db/Users.php');
 require_once(dirname(__FILE__, 2).'/class/db/Updates.php');

//ログインしてなければログイン画面へ
if(empty($_SESSION['user_info'])){
    header('Location:../sign/sign_in.php');
}


try{
    //ページの内容
    $update_contents = $_SESSION['page']['update_contents'];
    $update_contents['page_id'] = $_SESSION['page']['page_id'];

    var_dump($update_contents);

    $update = new Updates;

    if($_SESSION['page']['page_type'] === 1){
        $update_contents_done = $update->updatePageContentsA($update_contents);
        echo $update_contents_done;
        exit;
    }elseif($_SESSION['page']['page_type'] === 2){
        print_r($update_contents);
        /* $update_contents_done = $update->updatePageContentsB($update_contents);
        echo $update_contents_done; */
        exit;
    }else{
        $_SESSION['msg']['error'][] = Config::MSG_EXCEPTION;
        //header('Location:../mem/mem_top.php');
        exit;
    }
    
}catch(Exception $e){
    $_SESSION['msg']['error'][] = Config::MSG_EXCEPTION;
    //header('Location:../mem/mem_top.php');
    exit;
}

?>
