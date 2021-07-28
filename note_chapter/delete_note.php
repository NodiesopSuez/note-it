<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
 require_once(dirname(__FILE__, 2).'/class/config/Config.php');
 require_once(dirname(__FILE__, 2).'/class/util/Utility.php');
 require_once(dirname(__FILE__, 2).'/class/db/Connect.php');
 require_once(dirname(__FILE__, 2).'/class/db/Users.php');
 require_once(dirname(__FILE__, 2).'/class/db/Searches.php');
 require_once(dirname(__FILE__, 2).'/class/db/Deletes.php');

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
if(!empty($_SESSION['msg'])){
    $_SESSION['msg'] = array();
}

$note_id = $_POST['note_id'];

try{
    $search = new Searches;
    $delete = new Deletes;

    $chapter_list = $search->findChapterInfo('note_id', $note_id); //チャプターリスト
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
        $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    }else{
        $_SESSION['msg'] = ['okmsg' => ['ノートを削除できました']];
    }
    
    header('Location:../mem/mem_top.php');
    exit;

}catch(Exception $e){
    echo $e->getMessage();
    $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    header('Location:../mem/mem_top.php');
    exit;
}

?>