<?php 

include(dirname(__FILE__, 3).'/common/redirect.php');

authenticateError();

//必要ファイル呼び出し
require_once(dirname(__FILE__, 3).'/config/Connect.php');
require_once(dirname(__FILE__, 3).'/models/Users.php');
require_once(dirname(__FILE__, 3).'/models/Updates.php');
require_once(dirname(__FILE__, 3).'/models/Deletes.php');
require_once(dirname(__FILE__, 3).'/models/Additions.php');

try{
    //ページの内容
    $update_contents = ['contents'   => $_SESSION['page']['update_contents'], 
                        'page_id'    => $_SESSION['page']['page_id'], 
                        'page_title' => $_SESSION['page']['page_title']];

    $update   = new Updates;
    $delete   = new Deletes;
    $addition = new Addition;

    if($_SESSION['page']['page_type'] === 1){

        print_r($update_contents);
        $update_bool = $update->updatePageContentsA($update_contents);
    }elseif($_SESSION['page']['page_type'] === 2){
        $update_done[] = $update->updatePageContentsB($update_contents);
        $update_done[] = $delete->deletePageContents('page', 2, $update_contents['page_id'], 'update');
        $update_done[] = $addition->registerContentsB($update_contents['page_id'], $update_contents['contents']);
        $update_bool = in_array(0, $update_done, true) ? false : true ;
    }else{
        catchException();
    }
    
    if($update_bool === false){
        $_SESSION['bool'] = 'false';
        $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    }elseif($update_bool === true){
        $_SESSION['bool'] = 'ok';
        $_SESSION['msg'] = ['okmsg' => ['ページを更新できました!']];
    }

    $update   = null;
    $delete   = null;
    $addition = null;

    header('Location:/views/user/user_top.php');
    exit;
    
}catch(Exception $e){
    catchException();
}

?>
