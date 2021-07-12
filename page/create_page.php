<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once(dirname(__FILE__, 2).'/class/config/Config.php');
require_once(dirname(__FILE__, 2).'/class/util/Utility.php');
require_once(dirname(__FILE__, 2).'/class/db/Connect.php');
require_once(dirname(__FILE__, 2).'/class/db/Users.php');
require_once(dirname(__FILE__, 2).'/class/db/Searches.php');

//ログインしてなければログイン画面へ
if(empty($_SESSION['user_info'])){
    header('Location:../sign/sign_in.php');
}

$color = 'basic'; //ヘッダーメニューのカラークラス

//既存ノートリスト取得
$user_id = $_SESSION['user_info']['user_id'];
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
$show_msg = count($msg)>=2 ? implode("<br/>", $msg) : $msg[0];
$color_list = ['blue', 'pink', 'yellow', 'green', 'purple'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(dirname(__FILE__, 2).'/head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="../main/color_template.css">
    <link rel="stylesheet" type="text/css" href="./page.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="./create_page.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../inclusion/top_header.css">
</head>
<body>
    <div class="container">
        <?php include(dirname(__FILE__, 2).'/inclusion/mem_header.php')?>

        <div class="ladybug">
            <div class="balloon">
                <div class="msg">
                    <?= $show_msg ?>
                </div>
                <div class="tail"></div>
            </div>
            <img src="<?= $ladybug_img ?>">
        </div>

        <form method="post" action="./create_page_check.php" enctype="multipart/form-data">
            <!--ワンタイムトークン発生-->
            <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">       
            
            <!-- ノート -->
            <section class="note_section">

                <!-- 新規ノート選択ボタン -->

                <!-- 新規ノートカラーリスト -->
                    
                <!-- 新規ノートタイトル入力フォーム -->
                    
                <!-- 既存ノートリスト -->
                        
                <!-- 既存ノート選択ボタン -->

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

                <!-- チャプターカセット -->

                <!-- ページタイプ -->

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

                <!-- TypeB -->

            </section>
            <!-- 送信ボタン -->
        </form>
    </div>
    <!-- jQurery -->
    <script>let php = { user_id : "<?php echo $user_id; ?>"}; </script>
    <script src="../inclusion/inclusion.js" type="text/javascript"></script>
    <script src="./create_page.js" type="text/javascript"></script>
    <script src="./edit_page.js" type="text/javascript"></script>
</body>
</html>