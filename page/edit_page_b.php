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
	$_SESSION['msg']['error'][] = Config::MSG_INVALID_PROCESS;
	header('Location: ../sign/sign_in.php');
	exit;
}

if(is_numeric($_POST['set_page_id'])){
    $page_id = $_POST['set_page_id'];
}else{
    $_SESSION['msg']['error'][] = Config::MSG_INVALID_PROCESS;
    header('Location: ../sign/sign_in.php');
    exit;
}

try {
    $search  = new Searches;
    $utility = new SaftyUtil;

    //ページとコンテンツ情報
    $get_page_info = $search->findPageContentsB($page_id);
    $page_info     = $utility->sanitize(2, $get_page_info['page']);
    //foreach($get_page_info['contents'] as $contents){
    for($i=0 ; $i<count($get_page_info['contents']) ; $i++ ){
        $page_contents[] = $utility->sanitize(2, $get_page_info['contents'][$i]);
        $_SESSION['contents']['contents_'.$i+1] = ['file_type' => $page_contents[$i]['file_type'], 'data' => $page_contents[$i]['data']];
    }


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
    $_SESSION['msg']['error'][] = Config::MSG_EXCEPTION;
    header('Location:../mem/mem_top.php');
    exit;
}

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(dirname(__FILE__, 2).'/head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="../main/color_template.css">
    <link rel="stylesheet" type="text/css" href="./page.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../inclusion/top_header.css">
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

            <form class="edit" method="post" action="../page/edit_page_check.php" enctype="multipart/form-data">
                <!--ワンタイムトークン発生-->
                <input type="hidden" name="[page]token" value="<?= SaftyUtil::generateToken() ?>">
                <input type="hidden" name="[page]page_id" value="<?= $page_id ?>">
                <input type="hidden" name="[page]page_type" value="2">
                <div class="page_base b">

                    <input class="page_title" type="text" name="[page]page_title_b" value="<?= $page_info['page_title']?>">

                    <?php foreach($page_contents as $i=>$val) :?>
                        <div class="form_block" id="<?= $i+1 ?>_form_block">
                            <?php $num = $i+1; ?>
                            <?php if($val['file_type'] === 'text') :?>
                                <textarea class="contents text" id="contents_<?= $num ?>" name="[page]contents_<?= $num ?>">
                                    <?= $val['data'] ?>
                                </textarea>
                            <?php elseif($val['file_type'] === 'img') :?>
                                <img id="thumb_<?= $num ?>" src="<?= $val['data'] ?>">
                                <input class="contents img" type="file" id="contents_<?= $num ?>" name="contents_<?= $num ?>" accept="image/*" style="display:none">
                                <label class="change_img_btn" for="contents_<?= $num ?>" id="label_for_<?= $num ?>" >
                                    <img class="edit_icon" src="../page/img/edit_btn.svg">
                                </label>
                                <?php endif ?>
                            <button class="delete_btn" val="<?= $val['data'] ?>" role="button">
                                <img class="delete_icon" src="../page/img/delete_btn.svg">
                            </button>
                        </div>
                    <?php endforeach ?>

                    <!-- ボタンリスト -->
                    <div class="add_contents row">
                        <!-- テキスト追加ボタン -->
                        <button id="add_text" class="btn" type="button">+ text</button>
                        <!-- 画像追加ボタン -->
                        <button id="add_img" class="btn" type="button">+ image</button>
                    </div>
                </div> 


                <div class="buttons row">
                    <a class="back" href="../mem/mem_top.php">    
                        back
                    </a>
                    <button role="submit" class="submit  <?= $color ?>">submit</button>
                </div>
            </form>
    </div>
    <script src="../inclusion/inclusion.js" type="text/javascript"></script>
    <script src="../page/edit_page.js" type="text/javascript"></script>
</body>
</html>