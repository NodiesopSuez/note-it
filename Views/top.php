<?php
session_start();
session_regenerate_id();

//外部ファイル読込
require_once(dirname(__FILE__, 2).'/config/Icons.php');

$menus = ['news'=>'blue','story'=>'pink','feature'=>'yellow', 'Q & A'=>'green'];

$news_content = [
    'title' => 'New Color Lineup',
    'detail' => 'ノートのカラーを追加！',
    'date' => '2021.03.16'];

$img = dirname(__FILE__, 2).'/public/img/ladybug_nm.png';
$msg = 'ようこそ♪'; 


//sign_in用
$_SESSION['msg'] = array();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(dirname(__FILE__, 2).'/head.php')?>
    <link rel="stylesheet" type="text/css" href=".<?= dirname(__FILE__, 1).'/public/css/template.css' ?>">
    <link rel="stylesheet" type="text/css" href=".<?= dirname(__FILE__, 1).'/public/css/color_template.css' ?>">
    <link rel="stylesheet" type="text/css" href=".<?= dirname(__FILE__, 1).'/public/css/top_wide.css' ?>" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href=".<?= dirname(__FILE__, 1).'/public/css/top_narrow.css' ?>" media="screen and (max-width:1023px)">
    <link rel="stylesheet" type="text/css" href=".<?= dirname(__FILE__, 1).'/public/css/template.css' ?>">
    <link rel="stylesheet" type="text/css" href=".<?= dirname(__FILE__, 1).'/public/css/top_header.css' ?>">
</head>
<body>
   <div class="container">
    <?php include(dirname(__FILE__).'/top_header.php')?>
        <section class="top">
            <div class="catch_logo">
                <svg class="logo_mark" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 66.9 66.62"><?=Icons::LOGO_MARK?></svg>
                <svg class="logo_type" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 81.8 22.6"><?=Icons::LOGO_TYPE?></svg>
            </div>
            <p class="p_bold">紙も時間も無駄にして困り果てた<br/><span class="n_line">初心者プログラマーがつくった</span><span class="n_line">クラウドノート</span></p>
            
            <div class="sign_nav">
                <a class="sign_in" href="<?= dirname(__FILE__, 2).'/Views/user/sign_in.php' ?>"> 
                    <svg class="triangle_base" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 80 90">
                        <defs>
                            <linearGradient id="sign_in_gra" gradientUnits="userSpaceOnUse" x1="70%" y1="100%" x2="0%" y2="0%">
                                <stop  offset="0%" style="stop-color:#8595ee"/>
                                <stop  offset="40%" style="stop-color:#292749"/>
                                <stop  offset="100%" style="stop-color:#020126"/>
                            </linearGradient>
                            <linearGradient id="sign_up_gra" gradientUnits="userSpaceOnUse" x1="70%" y1="100%" x2="0%" y2="0%">
                                <stop  offset="10%" style="stop-color:#020126"/>
                                <stop  offset="60%" style="stop-color:#292749"/>
                                <stop  offset="100%" style="stop-color:#8595ee"/>
                            </linearGradient>
                        </defs>
                        <?=Icons::TRIANGLE?>
                    </svg>
                    <svg class="triangle" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 80 90"><?=Icons::TRIANGLE?></svg>
                </a>
                <div>
                    <div class="balloon">
                        <div class="msg">
                            ようこそ♪
                        </div>
                        <div class="tail"></div>
                    </div>
                    <img src="<?= $img ?>">
                </div>            
                <a class="sign_up" href="<?= dirname(__FILE__, 2).'/Views/user/sign_up.php' ?>">
                    <svg class="triangle_base" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 80 90"><?=Icons::TRIANGLE?></svg>
                    <svg class="triangle" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 80 90"><?=Icons::TRIANGLE?></svg>
                </a>
            </div>
            <div class="note_nav">
                <?php foreach($menus as $menu => $color): ?>
                    <button class="note <?= $color ?>">
                        <div class="note_base"></div>
                        <div class="note_title">
                            <p><?= $menu ?></p>
                        </div>
                        <div class="back_cover"></div>
                    </button>
                <?php endforeach?>
            </div>
        </section>
        <section class="news">
            <img src="<?= dirname(__FILE__, 2).'/public/img/header_news.png' ?>">
            <form action="./news.php">
                <ul>
                    <?php for($i=0; $i<=5; $i++): ?>
                        <li>
                            <button class="page news_icon blue" value="">
                                <div class="wrapback"></div>
                                <h1><?= $news_content['title'] ?></h1>
                                <p><?= $news_content['detail'] ?><br/>
                                    <?=$news_content['date'] ?></p>
                            </button>
                        </li>
                    <?php endfor; ?>
                </ul>
            </form>
        </section>
        <section class="feature">
            <img src="<?= dirname(__FILE__, 2).'/public/img/header_feature.png' ?>">
            <h1>3 FEATURES OF 'note IT'</h1>
            <div class="feature_contents">
                <div>
                    <img src="<?= dirname(__FILE__, 2).'/public/img/feature_sepalete.png' ?>">
                    <p>ノートからさらに<br/>チャプターへ分けて<br/>ページを保存できます。</p>
                </div>
                <div>
                    <img src="<?= dirname(__FILE__, 2).'/public/img/feature_color.png' ?>">
                    <p>ラインナップから<br/>好きなカラーを選んで<br/>保存できます。</p>
                </div>
                <div>
                    <img src="<?= dirname(__FILE__, 2).'/public/img/feature_type.png' ?>">
                    <p>チャプターごとに<br/>ページのタイプを選べます。</p>
                    <p class="type">Type A:単語帳タイプ<br/>Type B:フリータイプ</p>
                </div>
            </div>
        </section>
        <section class="story">
            <img src="<?= dirname(__FILE__, 2).'/public/img/header_story.png' ?>">
            <div class="story_frame">
                <div class="first_contents">
                    <h1>DEVELOPER'S DESIRE</h1>
                    <h1>OF SELF-SOlVING</h1>
                    <h1>IS THE BEGINNING</h1>
                    <p class="p_bold">「抱えている問題を自分のつくるプログラムで解決してみたい」<br/>
                        そんな私の挑戦心がnote ITのはじまり。</p>
                </div>  
                <div class="second_contents">
                    <img src="<?= dirname(__FILE__, 2).'/public/img/story_1.png' ?>">
                    <p>プログラマーとして活躍するべく、学習を始めた私。<br/>
                        学習した内容を忘れないためにも、<br/>
                        都度その内容をノートに書き留めていました。</p>
                </div> 
                <div class="third_contents">
                    <p>学習を進めたい気持ちはありつつも<br/>
                        「綺麗にノートを書きたい」という<br/>
                        こだわりを諦められず…</p>
                    <p>一文字でも失敗したと感じたら<br/>
                        新しいページへ書き直すを繰り返して…<br/>
                        紙も時間も無駄にしていました。</p>
                    <img src="<?= dirname(__FILE__, 2).'/public/img/story_2.png' ?>">
                </div> 
                <div class="forth_contents">
                    <div class="balloon">
                        <p>自分でクラウドノートを作れば<br/>
                            プログラミングの勉強にもなるし、<br/>
                            ほんの少しでも<br/>
                            同じような悩みを抱える人の<br/>
                            手助けになるかもしれない…</p>
                        <img src="<?= dirname(__FILE__, 2).'/public/img/bubble_lg.png' ?>">
                    </div>
                    <img src="<?= dirname(__FILE__, 2).'/public/img/story_3.png' ?>">
                    <p>この「ある日の思いつき」が<br/>
                        note ITは生まれました。</p>
                </div>
                <div class="fifth_contents">
                    <img src="<?= dirname(__FILE__, 2).'/public/img/story_4.png' ?>">
                    <h1>IMPROVING OF DEVELOPER'S SKILL<br/>
                        ADD MORE FEATURES OF 'note IT'</h1>
                    <p class="p_bold">私がプログラマーとして成長し続ける限り、note ITも成長し続けます。</p>
                    <p>初心者プログラマーがつくったクラウドノートなので<br/>
                        世に出ているノートアプリに比べて、昨日もデザインもまだまだ。<br/>
                        私のスキルが成長するとともに、note ITも成長します。<br/>
                        ご利用される方におきましてはご不便をかけるかもしれませんが、<br/>
                        暖かく見守りながら使っていただけると幸いです。</p> 
                </div>
                <a class="note blue lets_signin" href="<?= dirname(__FILE__, 2).'/Views/user/sign_up.php' ?>">
                    <div class="note_base"></div>
                    <div class="note_title">
                        <p>Let's<br/>
                        Sign Up!!</p>
                    </div>
                    <div class="back_cover"></div>
                </a>
                <p>さぁ、ノートを作ってみましょう！</p>
            </div>
        </section>
    </div>

    <script src="<?= dirname(__FILE__, 2).'/public/js/inclusion.js' ?>" type="text/javascript"></script>
    <script src="<?= dirname(__FILE__, 2).'/public/js/top.js' ?>" type="text/javascript"></script>
</body>
</html>