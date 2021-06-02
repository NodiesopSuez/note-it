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
    foreach($chapter_list as $chapter_id => $val){
        $val['page_type'] == 1 ? $page_a[] = $chapter_id : null; //typeAのチャプター
        $val['page_type'] == 2 ? $page_b[] = $chapter_id : null; //typeBのチャプター
    }

    //SQLへ渡す用にstring型へ
    if(!empty($page_a) && count($page_a) > 1){
        $page_a_str = implode(',', $page_a);
    }elseif(!empty($page_a) && count($page_a) === 1) {
        $page_a_str = implode($page_a);
    }
    if(!empty($page_b) && count($page_b) > 1){
        $page_b_str = implode(',', $page_b);
    }elseif(!empty($page_b) && count($page_b) === 1) {
        $page_b_str = implode($page_b);
    }

    echo '<br/><br/>page_a<br/>';
    var_dump($page_a);
    echo $page_a_str;
    echo '<br/><br/>page_b<br/>';
    if (isset($page_b)) {
        var_dump($page_b);
        echo $page_b_str;
    }

    //typeBの画像ファイルを削除
    if(isset($page_b) && count($page_b) >=1){
        $remove_files = $delete->removeFiles('note_chapter', $page_b_str);
        var_dump($remove_files);
    }


}catch(Exception $e){

}

?>