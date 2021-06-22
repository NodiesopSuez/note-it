<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/config/Icons.php');
require_once('../class/util/Utility.php');
require_once('../class/db/Connect.php');
require_once('../class/db/Searches.php');

//余計な情報を削除
$_SESSION['error'] = array();

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

extract($_POST);

try{
    $search = new Searches;
    $utility = new SaftyUtil;

    $get_page_contents = $search->findPageContentsA($page_id);
    $get_page_contents = $utility->sanitize(2, $get_page_contents);
    foreach($get_page_contents as $key => $val){
        $page_contents[$key] = nl2br($val);
    }
    extract($page_contents);

    $chapter_id   = $get_page_contents['chapter_id'];
    $chapter_info = $search->findChapterInfo('chapter_id', $chapter_id);
    $chapter_info = $utility->sanitize(2, $chapter_info[$chapter_id]);
    extract($chapter_info);

    $note_info    = $search->findNoteInfo('note_id', $note_id);
    $note_info    = $utility->sanitize(2, $note_info[$note_id]);
    extract($note_info);

}catch(Exception $e){
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
    header('Location:../mem/mem_top.php');
    exit;
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include('../head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="../main/color_template.css">
    <link rel="stylesheet" type="text/css" href="./page.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../inclusion/top_header.css">
</head>
<body>
    <div class="container  <?= $color ?>">
        <?php include('../inclusion/mem_header.php')?>
            <div class="titles">
                <div class="note">
                    <div class="note_base"></div>
                    <div class="note_title">
                        <p><?= $note_title ?></p>
                    </div>
                    <div class="back_cover"></div>
                </div>
                <div class="chapter">
                    <p><?= $chapter_title ?></p>
                </div>
            </div>
            <div class="page_menu">
                <form class="edit" method="post" action="../page/edit_page_a.php">
                    <!--ワンタイムトークン発生-->
                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                    <input type="hidden" name="set_page_id" value="<?= $page_id ?>">
                    <button class="edit_btn">
                        <svg class="edit_icon" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300"><?=Icons::EDIT ?></svg>
                    </button>
                </form>
                <form class="delete" method="post" action="../page/delete_page.php">
                    <!--ワンタイムトークン発生-->
                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                    <input type="hidden" name="set_page_id" value="<?= $paeg_id ?>">
                    <button class="delete_btn">
                        <svg class="delete_icon" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300"><?=Icons::DELETE ?></svg>
                    </button>
                </form>
            </div>
            <div class="page_base">
                <div class="wrapback"></div>
                <div class="page_title"><?= $page_title ?></div>
                <?php if(!$meaning  == '') :?><div class="meaning"><?= $meaning ?></div><?php endif ?>
                <?php if(!$syntax   == '') :?><div class="syntax"><?= $syntax ?></div><?php endif ?>
                <?php if(!$syn_memo == ''):?><div class="syn_memo"><?= $syn_memo ?></div><?php endif ?>
                <?php if(!$example == '' && !$ex_memo == ''):?>
                    <div class="example">
                        <div class="ex"><?= $example ?></div>
                        <div class="ex_memo"><?= $ex_memo ?></div>
                    </div>
                <?php endif ?>
                <?php if(!$memo == '') :?><div class="memo"><?= $memo ?></div><?php endif ?>
            </div>
            <a class="back" href="../mem/mem_top.php">    
                back
            </a>
    </div>
    <script src="../inclusion/inclusion.js" type="text/javascript"></script>
</body>
</html>