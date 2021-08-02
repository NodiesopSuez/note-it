<?php

include(dirname(__FILE__, 3).'/controllers/page/show_edit_page_a.php');

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(dirname(__FILE__, 2).'/head.php')?>
    <link rel="stylesheet" type="text/css" href="/public/css/template.css">
    <link rel="stylesheet" type="text/css" href="/public/css/color_template.css">
    <link rel="stylesheet" type="text/css" href="/public/css/page.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="/public/css/top_header.css">
</head>
<body>
    <div class="container <?= $color ?>">
        <?php include(dirname(__FILE__, 2).'/views/user_header.php')?>
        
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

            <form class="edit" method="post" action="/controllers/page/edit_page_check.php">
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
                    <a class="back" href="/mem/user_top.php">    
                        back
                    </a>
                    <button role="submit" class="submit <?= $color ?>">submit</button>
                </div>
            </form>
    </div>
    <script src="/public/js/inclusion.js" type="text/javascript"></script>
    <script src="/public/js/edit_page.js" type="text/javascript"></script>
</body>
</html>