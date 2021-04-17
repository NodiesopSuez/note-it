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

//余計な情報を削除
$_SESSION['error'] = array();

//既存ノートリスト取得
$searches = new Searches;
$note_list = $searches->findNoteInfo('user_id', 4/* $user_id */);
print_r($note_list);

$msg =['エラー！<br/>申し訳ございませんが<br/>最初からお進みください'];
$msg = ['Which Note Type?'];

$color_list = ['blue' => 'ブルー', 'pink' => 'ピンク', 'yellow' => 'イエロー', 'green' => 'グリーン', 'purple' => 'パープル'];
$ladybug = '../page/img/ladybug_nm.png';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include('../head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="./create_page.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../inclusion/mem_header.css" media="screen and (min-width:1024px)">
</head>
<body>
    <div class="container">
        <?php include('../inclusion/mem_header.php')?>

        <div class="ladybug">
            <img src="<?= $ladybug ?>">
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
                <input name="note_existence" value="new" type="radio" id="new_note">
                <label for="new_note">
                    <div class="note">
                        <div class="note_base"></div>
                        <div class="note_title">
                            <p>NEW NOTE</p>
                        </div>
                        <div class="back_cover"></div>
                    </div>
                </label>
                <!-- 新規ノートカラーリスト -->
                <?php foreach($color_list as $color => $jp): ?>
                    <input name="note_color" value="<?= $color ?>" type="radio" id="new_<?= $color ?>">
                    <label for="new_<?= $color ?>">
                        <div class="note">
                            <div class="note_base"></div>
                            <div class="note_title">
                                <p><?= $jp ?></p>
                            </div>
                            <div class="back_cover"></div>
                        </div>
                    </label>
                    <?php endforeach ?>
                    
                    <!-- 新規ノートタイトル入力フォーム -->
                    <div class="note">
                        <div class="note_base"></div>
                        <div class="note_title">
                            <textarea name="new_note_title" type="text" ></textarea>
                        </div>
                        <div class="back_cover"></div>
                    </div>
                    
                    <!-- 既存ノートリスト -->
                    <?php foreach($note_list as $note_id => $key): ?>
                        <input name="note_id" value="<?= $note_id ?>" type="radio" id="note_<?= $note_id ?>">
                        <label for="note_<?= $note_id ?>">
                            <div class="note <?= $key['color'] ?>">
                                <div class="note_base"></div>
                                <div class="note_title">
                                    <p><?= $key['note_title'] ?></p>
                                </div>
                                <div class="back_cover"></div>
                            </div>
                        </label>
                        <?php endforeach ?>
                        
                        <!-- 既存ノート選択ボタン -->
                        <input name="note_existence" value="exist" type="radio" id="exist_note">
                        <label for="exist_note">
                            <div class="note">
                                <div class="note_base"></div>
                                <div class="note_title">
                                    <p>EXIST NOTE</p>
                                </div>
                                <div class="back_cover"></div>
                            </div>
                        </label>
                        
                    </section>
                    
                    
                    
                    
                    
                    
                    <!--チャプター -->
                    <div class="chapter_section">
                        <div>
                            <!-- 新規か既存か -->
                    <div class="new_or_exist">
                        <button class="chapter">
                            <p>new</p>
                        </button>
                        <button class="chapter">
                            <p>exist</p>
                        </button>
                    </div>
                    <!-- 新規：チャプター名入力フォーム -->
                    <div class="new_chapter_form">
                        <div class="chapter">
                            <input type="text" namge="new_chapter_title" placeholder="チャプター名">
                        </div>
                        <button class="chapter">
                            <p>exist</p>
                        </button>
                    </div>
                </div>
                <div class="ex_chapter_list">
                    <button class="chapter">
                        <p>既存チャプター</p>
                    </button>
                    <button class="chapter">
                        <p>new</p>
                    </button>
                </div>
            </div>








            <!-- ページタイプ -->
            <div class="page_type">
                <button class="page type">
                    <div class="wrapback"></div>
                    <p>Type A</p>
                </button>
                <button class="page type">
                    <div class="wrapback"></div>
                    <p>Type B</p>
                </button>
            </div>
            <div class="page_forms">
                <!-- TypeA:入力フォーム -->
                <div class="page_base a">
                    <input type="text" name="page_title_a" class="page_title" placeholder="ページタイトル">
                    <input type="text" name="meaning" class="meaning" placeholder="意味">
                    <input type="text" name="syntax" class="syntax" placeholder="構文">
                    <textarea name="syn_memo" class="syn_memo">syn_memo</textarea>
                    <div class="example">
                        <textarea name="example" class="ex">
                            exampleexampleexampleexampleexampleexampleexampleexampleexampleexampleexampleexampleexample
                        </textarea>
                        <textarea name="ex_memo" class="ex_memo">example</textarea>
                    </div>
                    <textarea name="memo" class="memo">memo</textarea>
                </div>

                <!-- TypeB:入力フォーム -->
                <div class="page_base b">
                    <input type="text" name="page_title_b" class="page_title" placeholder="ページタイトル">
                    <div class="form_block" id="form_block_1">
                        <div class="contents text" id="contents_1" contentEditable="true"></div>
                        <input type="hidden" id="hid_contents_1" name="contents_1" value="">
                    </div>
                    <div class="buttons row">
                        <!--テキスト追加ボタン-->
                        <button id="add_text" class="btn m-0" type="button"><img src="" style="width:2rem"></button>
                        <!--画像追加ボタン-->
                        <button id="add_img" class="btn m-0" type="button"><img src="" style="width:2rem"></button>
                        <!--コード追加ボタン-->
                        <!--<button id="add_code" class="btn btn-secondary my-1" type="button">コードを追加する</button>
                        <button id="add_quote" class="btn btn-secondary my-1" type="button">引用を追加する</button>-->
                    </div>
                </div>
            </div>
            <button role="submit" class="submit">submit</button>
        </form>
    </div>
</body>
</html>