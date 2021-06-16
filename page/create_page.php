<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/util/Utility.php');
require_once('../class/db/Connect.php');
require_once('../class/db/Users.php');
require_once('../class/db/Searches.php');

//ログインしてなければログイン画面へ
/* if(empty($_SESSION['user_data'])){
    header('Location:../sign/sign_in.php');
} */

$color = 'basic'; //ヘッダーメニューのカラークラス

//既存ノートリスト取得
$user_id = 4;//$_SESSION['user_info']['user_id'];
$searches = new Searches;
$note_list = $searches->findNoteInfo('user_id', 4/* $user_id */);

//エラーの有無によってテントウの表示を分岐
if(!empty($_SESSION['error'])){
    $ladybug_img = './img/ladybug_sd.png';
	$msg = $_SESSION['error'];
}elseif(!empty($_SESSION['okmsg'])){
    $ladybug_img = './img/ladybug_nm.png';
    $msg = $_SESSION['okmsg'];
    $_SESSION['okmsg'] = array();
}else{
    $ladybug_img = './img/ladybug_nm.png';
    $msg =  ['どのノートに追加しますか？'];
}

$color_list = ['blue', 'pink', 'yellow', 'green', 'purple'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include('../head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="../main/color_template.css">
    <link rel="stylesheet" type="text/css" href="./page.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="./create_page.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../inclusion/mem_header.css" media="screen and (min-width:1024px)">
</head>
<body>
    <div class="container">
        <?php include('../inclusion/mem_header.php')?>

        <div class="ladybug">
            <img src="<?= $ladybug_img ?>">
            <div class="balloon">
                <?php foreach($msg as $m): ?>
                <?= $m ?><br/>
                <?php endforeach ?>
            </div>
        </div>

        <form method="post" action="./create_page_check.php" enctype="multipart/form-data">
            <!--ワンタイムトークン発生-->
            <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">       
            
            <!-- ノート -->
            <section class="note_section">

                <!-- 新規ノート選択ボタン -->
                <!-- <label for="new_note">
                    <input name="note_existence" value="new" type="radio" id="new_note">
                    <div class="note basic">
                        <div class="note_base"></div>
                        <div class="note_title">
                            <p>NEW NOTE</p>
                        </div>
                        <div class="back_cover"></div>
                    </div>
                </label> -->

                <!-- 新規ノートカラーリスト -->
                <!-- <?php foreach($color_list as $color): ?>
                    <label for="new_<?= $color ?>" class="color_label">
                        <input name="note_color" value="<?= $color ?>" type="radio" id="new_<?= $color ?>">
                        <div class="note <?= $color ?>">
                            <div class="note_base"></div>
                            <div class="note_title">
                                <p><?= $color ?></p>
                            </div>
                            <div class="back_cover"></div>
                        </div>
                    </label>
                <?php endforeach ?>  -->
                    
                <!-- 新規ノートタイトル入力フォーム -->
                <!-- <div class="note note_title_form">
                    <div class="note_base"></div>
                    <div class="note_title">
                        <textarea name="new_note_title"></textarea>
                    </div>
                    <div class="back_cover"></div>
                </div> -->
                    
                <!-- 既存ノートリスト -->
                <!-- <?php foreach($note_list as $note_id => $key): ?>
                    <label for="note_<?= $note_id ?>" class="exist_note_list">
                        <input name="note_id" value="<?= $note_id ?>" type="radio" id="note_<?= $note_id ?>">
                        <div class="note <?= $key['color'] ?>">
                            <div class="note_base"></div>
                            <div class="note_title">
                                <p><?= $key['note_title'] ?></p>
                            </div>
                            <div class="back_cover"></div>
                        </div>
                    </label>
                <?php endforeach ?> -->
                        
                <!-- 既存ノート選択ボタン -->
                <!-- <input name="note_existence" value="exist" type="radio" id="exist_note">
                <label for="exist_note">
                    <div class="note">
                        <div class="note_base"></div>
                        <div class="note_title">
                            <p>EXIST NOTENOTENOTENOTE</p>
                        </div>
                        <div class="back_cover"></div>
                    </div>
                </label> -->
            </section>
                    
            <!--チャプター -->
            <section class="chapter_section">

                <!-- ノートカセット -->
                <div class="note cassette">
                    <div class="note_base"></div>
                    <div class="note_title">
                        <p></p>
                    </div>
                    <div class="back_cover"></div>
                </div>
                
                <div>
                    <!-- 新規チャプター選択ボタン -->
                    <label for="new_chapter">
                        <div class="chapter">
                            <p>NEW</p>
                        </div>
                    </label>
                    
                    <!-- 新規チャプタータイトル -->
                    <div class="chapter　new_chapter_title">
                        <input name="new_chapter_title" type="text">NEW
                    </div>
                    
                    <!-- 既存チャプターリスト -->
                    <label for="">
                        <input name="chapter_id" value="" type="radio" id="">
                        <div class="chapter">
                            <p></p>
                        </div>
                    </label>

                    <!-- 既存チャプター選択ボタン -->
                    <label for="exist_chapter">
                        <div class="chapter">
                            <p>EXIST</p>
                        </div>
                    </label> 
                </div>
            </section>

            <!-- ページタイプ -->
            <section class="page_type">

                <!-- ノートカセット -->
                <!-- <div class="note cassette">
                    <div class="note_base"></div>
                    <div class="note_title">
                        <p></p>
                    </div>
                    <div class="back_cover"></div>
                </div> -->

                <!-- チャプターカセット -->
                <!-- <div class="chapter chapter_cassette">
                    <p>NEW</p>
                </div> -->

                <!-- ページタイプ -->
                <!-- <label for="page_a">
                    <div class="page">
                        <div class="wrapback"></div>
                        <p>Type A</p>
                    </div>
                </label>
                <label for="page_b">
                    <div class="page">
                        <div class="wrapback"></div>
                        <p>Type B</p>
                    </div>
                </label> -->
            </section>
            
            <!-- radioボタン集約 -->
            <section class="radio_section">
                <!-- 既存ノートリストと既存チャプターリストはjsで生成 -->
                <!-- ノート：新規/既存 -->
                <input name="note_existence" value="new"   type="radio" id="new_note">
                <input name="note_existence" value="exist" type="radio" id="exist_note" checked>
                <!-- ノート：カラー -->
                <?php foreach($color_list as $color): ?>
                    <input name="note_color" value="<?= $color ?>" type="radio" id="new_<?= $color ?>">
                <?php endforeach ?>
                <!-- チャプター：新規/既存 -->
                <input name="chapter_existence" value="new"   type="radio" id="new_chapter">
                <input name="chapter_existence" value="exist" type="radio" id="exist_chapter">
                <!-- ページタイプ -->
                <input name="page_type" value="1" type="radio" id="page_a">
                <input name="page_type" value="2" type="radio" id="page_b">
            </section>

            <!-- コンテンツ入力フォーム -->
            <section class="contents_section">

                <!-- TypeA -->
                <!-- <div class="page_base a">
                    <input class="page_title_a" type="text" name="page_title_a" placeholder="ページタイトル">
                    <input class="meaning" type="text" name="meaning" placeholder="意味">
                    <input class="syntax" type="text" name="syntax" placeholder="構文">
                    <textarea class="syn_memo" name="syn_memo" placeholder="構文メモ"></textarea>
                    <div class="example">
                        <textarea class="ex" name="example" placeholder="例文"></textarea>
                        <textarea class="ex_memo" name="ex_memo" placeholder="例文メモ"></textarea>
                    </div>
                    <textarea class="memo" name="memo" placeholder="メモ"></textarea>
                </div> -->

                <!-- TypeB -->
                <!-- <div class="page_base b">
                    <input class="page_title" type="text" name="page_title_b" placeholder="ページタイトル">
                    <div class="form_block" id="form_block_1">
                        <div class="contents text" id="contents_1" contentEditable="true"></div>
                        <input id="hid_contents_1" type="hidden" name="contents_1" value="">
                    </div> -->

                    <!-- ボタンリスト -->
                    <!-- <div class="buttons row"> -->
                        <!-- テキスト追加ボタン -->
                        <!-- <button id="add_text" class="btn" type="button">テキストを追加する</button>-->
                        <!-- 画像追加ボタン -->
                        <!-- <button id="add_img" class="btn" type="button">画像を追加する</button> -->
                        <!-- コード追加ボタン -->
                        <!-- <button id="add_code" class="btn" type="button">コードを追加する</button> -->
                        <!-- 引用追加ボタン -->
                        <!-- <button id="add_quote" class="btn" type="button">引用を追加する</button> -->
                    <!-- </div>
                </div> -->

            </section>
            <!-- 送信ボタン -->
            <!-- <button role="submit" class="submit">submit</button> -->
        </form>
    </div>
    <!-- jQurery -->
    <script>let php = { user_id : "<?php echo $user_id; ?>"}; </script>
    <script src="./create_page.js" type="text/javascript"></script>
</body>
</html>