<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
 require_once(dirname(__FILE__, 2).'/config/Config.php');
 require_once(dirname(__FILE__, 2).'/class/config/Icons.php');
 require_once(dirname(__FILE__, 2).'/util/Utility.php');
 require_once(dirname(__FILE__, 2).'/config/Connect.php');
 require_once(dirname(__FILE__, 2).'/models/Searches.php');

//余計な情報を削除
$_SESSION['msg'] = array();

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

extract($_POST);

try{
    $search = new Searches;
    $utility = new SaftyUtil;

    $get_page_contents = $search->findPageContentsB($page_id);

    $get_page_contents['page'] = $utility->sanitize(2, $get_page_contents['page']);
    extract($get_page_contents['page']);
    $_SESSION['page'] = [ 'page_id'=>(int)$page_id, 'page_type'=>2, 'page_title'=>$page_title, 'chapter_id'=>(int)$chapter_id ];
    
    $contents = $get_page_contents['contents'];
    for ($i=0; $i<count($contents); $i++) {
        $contents[$i] = $utility->sanitize(2, $contents[$i]);
        if($contents[$i]['file_type'] === 'text'){
            $contents[$i]['data'] = nl2br($contents[$i]['data']);
        }
    }
    
    $chapter_info = $search->findChapterInfo('chapter_id', $chapter_id);
    $chapter_info = $utility->sanitize(2, $chapter_info[$chapter_id]);
    extract($chapter_info);
    $note_info    = $search->findNoteInfo('note_id', (int)$note_id);
    $note_info    = $utility->sanitize(2, $note_info[$note_id]);
    extract($note_info);

}catch(Exception $e){
    $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    header('Location:../mem/user_top.php');
    exit;
}



?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(dirname(__FILE__, 2).'/head.php')?>
    <link rel="stylesheet" type="text/css" href="../public/css/template.css">
    <link rel="stylesheet" type="text/css" href="../public/css/color_template.css">
    <link rel="stylesheet" type="text/css" href="./page.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../public/css/top_header.css">
</head>
<body>
    <div class="container <?= $color ?>">
        <?php include(dirname(__FILE__, 2).'/views/user_header.php')?>
            <div class="titles">
                <div class="note">
                    <div class="note_base"></div>
                    <div class="note_title">
                        <p><?= $note_title ?></p>
                    </div>
                    <div class="back_cover"></div>
                </div>
                <div class="chapter  <?= $color ?>">
                    <p><?= $chapter_title ?></p>
                </div>
            </div> 
            <div class="page_menu">
                <form class="edit" method="post" action="../page/edit_page_b.php">
                    <!--ワンタイムトークン発生-->
                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                    <button class="edit_btn">
                        <svg class="edit_icon" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300"><?=Icons::EDIT ?></svg>
                    </button>
                </form>
                <form class="delete" method="post" action="../note/delete_note.php">
                    <!--ワンタイムトークン発生-->
                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                    <button class="delete_btn">
                        <svg class="delete_icon" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300"><?=Icons::DELETE ?></svg>
                    </button>
                </form>
            </div>
            <div class="page_base">
                <div class="wrapback"></div>
                <div class="page_title"><?= $page_title ?></div>
                <?php for($i=0; $i<count($contents); $i++):?>
                    <?php if($contents[$i]['file_type'] === 'text'):?>
                        <div class="text"><?= $contents[$i]['data'] ?></div>
                    <?php elseif($contents[$i]['file_type']=== 'img'):?>
                        <img class="img" src="<?= $contents[$i]['data']?>">
                    <?php endif ?>
                <?php endfor ?>
            </div>
            <a class="back" href="../mem/user_top.php">    
                back
            </a>
    </div>
    <script src="../inclusion/inclusion.js" type="text/javascript"></script>
</body>
</html>