<?php
session_start();
session_regenerate_id();

//外部ファイル読込
require_once('../class/config/Config.php');

$menus = ['news'=>'blue','story'=>'pink','feature'=>'yellow', 'Q & A'=>'green'];
$img = './img/ladybug_nm.png';
$msg = 'ようこそ♪'; 
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include('../head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="./top.css">
</head>
<body>
    <div class="container">
        <div class="catch_logo">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 66.9 66.62"><?=Config::LOGO_MARK?></svg>
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 81.8 22.6"><?=Config::LOGO_TYPE?></svg>
            <p>紙も時間も無駄にして困り果てた<br/>初心者プログラマーがつくったクラウドノート</p>
        </div>
        <div class="sign_nav">
            <button><p>sign in</p></button>
            <div>
                <img src="<?= $img ?>">
                <p><?= $msg ?></p>
            </div>            
            <button><p>sign in</p></button>
        </div>
        <div class="note_nav">
            <?php foreach($menus as $menu => $color): ?>
             <div class="<?= $color ?>"><?= $menu ?></div>
            <?php endforeach?>
        </div>
        
    </div>


    <script src="./top.js" type="text/javascript"></script>
</body>
</html>