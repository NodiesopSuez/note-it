<?php
session_start();
session_regenerate_id();

//外部ファイル読込
require_once('../class/config/Config.php');

$menus = ['news'=>'n_blue','story'=>'n_pink','feature'=>'n_yellow', 'Q & A'=>'n_green'];

$news_content = [
    'title' => 'New Color Lineup',
    'detail' => 'ノートのカラーを追加！',
    'date' => '2021.03.16'];

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
        <section class="top">
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
                <button><p>sign up</p></button>
            </div>
            <div class="note_nav">
                <?php foreach($menus as $menu => $color): ?>
                <div class="note <?= $color ?>">
                    <p><?= $menu ?></p>
                </div>
                <?php endforeach?>
            </div>
        </section>
        <section class="news">
            <img src="./img/header_news.png">
            <?php for($i=0; $i<=5; $i++): ?>
                <div class="news">
                    <h1><?= $news_content['title'] ?></h1>
                    <p><?= $news_content['detail'] ?><br/>
                        <?=$news_content['date'] ?></p>
                </div>
            <?php endfor; ?>
        </section>
        <section class="feature">
            <img src="./img/header_feature.png">
            <h1>3 FEATURES OF 'note IT'</h1>
            <div class="feature_contents">
                <div>
                    <img src="./img/feature_sepalete.png">
                    <p>ノートからさらにチャプターへ分けてページを保存できます。</p>
                </div>
                <div>
                    <img src="./img/feature_color.png">
                    <p>ラインナップから各ノートに好きなカラーを選んで保存できます。</p>
                </div>
                <div>
                    <img src="./img/feature_type.png">
                    <p>チャプターごとにページのタイプを選べます。<br/>
                    Type A:単語帳タイプ<br/>Type B:フリータイプ</p>
                </div>
            </div>
        </section>
        
    </div>


    <script src="./top.js" type="text/javascript"></script>
</body>
</html>