<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/config/Icons.php');
require_once('../class/util/Utility.php');



?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include('../head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="./page.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../inclusion/mem_header.css" media="screen and (min-width:1024px)">
</head>
<body>
    <div class="container">
        <?php include('../inclusion/mem_header.php')?>
        
            <div class="titles">
                <div class="note">
                    <div>
                        <p></p>
                    </div>
                </div>
                <div class="chapter">
                    <p></p>
                </div>
                <div class="page_menu">
                    <form class="edit" method="post" action="../note/edit_note.php">
                        <!--ワンタイムトークン発生-->
                        <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                        <input type="hidden" name="set_note_id" value=" note_id ">
                        <button class="edit_btn">
                            <svg class="edit_icon" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300"><?=Icons::EDIT ?></svg>
                        </button>
                    </form>
                    <form class="delete" method="post" action="../note/delete_note.php">
                        <!--ワンタイムトークン発生-->
                        <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                        <input type="hidden" name="set_note_id" value=" note_id ">
                        <button class="delete_btn">
                            <svg class="delete_icon" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300"><?=Icons::DELETE ?></svg>
                        </button>
                    </form>
                </div>
            </div>
        
            <div class="page_a">
                <div class="wrapback"></div>
                <div class="page_title">JavaScriptについて</div>
                <div class="meaning">meaning</div>
                <div class="syntax">syntax</div>
                <div class="syn_memo">syn_memo</div>
                <div class="example">
                    <div class="ex">exampleexampleexampleexampleexampleexampleexampleexampleexampleexampleexampleexampleexample</div>
                    <div class="ex_memo">example</div>
                </div>
                <div class="memo">memo</div>
            </div>
    
    </div>
</body>
</html>