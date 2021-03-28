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
            </div>

            <form class="edit" method="post" action="../page/edit_page_a_check.php">
                <!--ワンタイムトークン発生-->
                <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                <div class="page_base">
                    <div class="wrapback"></div>
                    <input type="text" name="page_title_a" class="page_title" placeholder="Title">
                    <input type="text" name="meaning" class="meaning" placeholder="Meaning">
                    <input type="text" name="syntax" class="syntax" placeholder="Syntax">
                    <textarea name="syn_memo" class="syn_memo">syn_memo</textarea>
                    <div class="example">
                        <textarea name="example" class="ex">exampleexampleexampleexampleexampleexampleexampleexampleexampleexampleexampleexampleexample</textarea>
                        <textarea name="ex_memo" class="ex_memo">example</textarea>
                    </div>
                    <textarea name="memo" class="memo">memo</textarea>
                </div>
                <a class="back" href="../mem/mem_top.php">    
                    back
                </a>
                <button role="submit" class="submit">submit</button>
            </form>
    </div>
</body>
</html>