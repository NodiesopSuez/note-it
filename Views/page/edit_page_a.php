<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
 require_once(dirname(__FILE__, 2).'/class/config/Config.php');
 require_once(dirname(__FILE__, 2).'/class/config/Icons.php');
 require_once(dirname(__FILE__, 2).'/class/db/Connect.php');
 require_once(dirname(__FILE__, 2).'/class/db/Searches.php');
 require_once(dirname(__FILE__, 2).'/class/util/Utility.php');

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

if(!empty($_SESSION['page'])){
    extract($_SESSION['page']);
}else{
    $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    header('Location: ../sign/sign_in.php');
    exit;
}

try {
    $search  = new Searches;
    $utility = new SaftyUtil;

    //ページとコンテンツ情報
    $get_page_info = $search->findPageContentsA($page_id);
    $page_info     = $utility->sanitize(2, $get_page_info);
    //チャプター情報
    $chapter_id   = $page_info['chapter_id'];
    $chapter_info = $search->findChapterInfo('chapter_id', $chapter_id);
    //ノート情報
    $note_id   = $chapter_info[$chapter_id]['note_id'];
    $note_info = $search->findNoteInfo('note_id', $note_id);

    $note_title = $utility->sanitize(4, $note_info[$note_id]['note_title']);
    $color      = $utility->sanitize(4, $note_info[$note_id]['color']);
    $chapter_title = $utility->sanitize(4, $chapter_info[$chapter_id]['chapter_title']);

    $search = null;

}catch(Exception $e){
    $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
    header('Location:../mem/mem_top.php');
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
        <?php include(dirname(__FILE__, 2).'/inclusion/mem_header.php')?>
        
            <div class="titles edit_page">
                <div class="note">
                    <div class="note_base"></div>
                    <div class="note_title">
                        <p><?= $note_title ?></p>
                    </div>
                    <div class="back_cover"></div>
                </div>
                <div class="chapter <?= $color ?>">
                    <p><?= $chapter_title?></p>
                </div>
            </div>

            <form class="edit" method="post" action="../page/edit_page_check.php">
                <!--ワンタイムトークン発生-->
                <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                <div class="page_base <?= $color ?>">
                    <div class="wrapback"></div>
                    <input type="text" name="page_title" class="page_title" value="<?= $page_info['page_title'] ?>">
                    <input type="text" name="meaning" class="meaning" value="<?= $page_info['meaning'] ?>">
                    <input type="text" name="syntax" class="syntax" value="<?= $page_info['syntax'] ?>">
                    <textarea name="syn_memo" class="syn_memo"><?= $page_info['syn_memo'] ?></textarea>
                    <div class="example">
                        <textarea name="example" class="ex"><?= $page_info['example'] ?></textarea>
                        <textarea name="ex_memo" class="ex_memo"><?= $page_info['ex_memo'] ?></textarea>
                    </div>
                    <textarea name="memo" class="memo"><?= $page_info['memo'] ?></textarea>
                </div>
                <div class="buttons row">
                    <a class="back" href="../mem/mem_top.php">    
                        back
                    </a>
                    <button role="submit" class="submit <?= $color ?>">submit</button>
                </div>
            </form>
    </div>
    <script src="../inclusion/inclusion.js" type="text/javascript"></script>
    <script src="../page/edit_page.js" type="text/javascript"></script>
</body>
</html>