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
        <section class="story">
            <img src="./img/header_story.png">
            <div class="first_contents">
                <h1>DEVELOPER'S DESIRE</h1>
                <h1>OF SELF-SOlVING</h1>
                <h1>IS THE BEGINNING</h1>
                <p>「抱えている問題を<br/>
                    自分のつくるプログラムで解決してみたい」<br/>
                    そんな私の挑戦心がnote ITのはじまり。</p>
            </div>  
            <div class="second_contents">
                <img src="./img/story_1.png">
                <p>プログラマーとして活躍するべく、学習を始めた私。<br/>
                    学習した内容を忘れないためにも、<br/>
                    都度その内容をノートに書き留めていました。</p>
            </div> 
            <div class="second_contents">
                <p>学習を進めたい気持ちはありつつも<br/>
                    「綺麗にノートを書きたい」という<br/>
                    こだわりを諦められず…</p>
                <p>一文字でも失敗したと感じたら<br/>
                    新しいページへ書き直す…<br/>
                    この繰り返しにより、<br/>
                    私は紙も時間も無駄にしていました。</p>
                <img src="./img/story_2.png">
            </div> 
            <div class="third_contnets">
                <div class="balloon">
                    <p>自分でクラウドノートを作れば<br/>
                        プログラミングの勉強にもなるし、<br/>
                        ほんの少しでも<br/>
                        同じような悩みを抱える人の<br/>
                        手助けになるかもしれない…</p>
                    <img src="./img/bubble_lg.png">
                    <img src="./img/bubble_md.png">
                    <img src="./img/bubble_sm.png"> 
                </div>
                <img src="./img/story_3.png">
                <p>この「ある日の思いつき」が<br/>
                    note ITは生まれました。</p>
            </div>
            <div class="forth_contents">
                <img src="./img/story_4.png">
                <h1>IMPROVING OF DEVELOPER'S SKILL<br/>
                    ADD MORE FEATURES OF 'note IT'</h1>
                <p>私がプログラマーとして成長し続ける限り、note ITも成長し続けます。</p>
                <p>初心者プログラマーがつくったクラウドノートなので<br/>
                    世に出ているノートアプリに比べて、昨日もデザインもまだまだ。<br/>
                    私のスキルが成長するとともに、note ITも成長します。<br/>
                    ご利用される方におきましてはご不便をかけるかもしれませんが、<br/>
                    暖かく見守りながら使っていただけると幸いです。</p>
            </div>
        </section>
    </div>


    <script src="./top.js" type="text/javascript"></script>
</body>
</html>