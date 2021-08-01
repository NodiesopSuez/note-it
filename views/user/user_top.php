<?php 

include(dirname(__FILE__, 2).'/controllers/show_user_top.php');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include(dirname(__FILE__, 3).'/head.php')?>
    <link rel="stylesheet" type="text/css" href="/public/css/template.css">
    <link rel="stylesheet" type="text/css" href="/public/css/color_template.css">
    <link rel="stylesheet" type="text/css" href="/public/css/mem_top.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="/public/css/top_header.css">
</head>
<body>
    <div class="container">
    <?php include(dirname(__FILE__, 2).'/mem_header.php')?>
        <!-- テントウメッセージ -->
        <section class="ladybug">
            <div class="balloon">
                <div class="msg">
                    <?= $show_msg ?>
                </div>
                <div class="tail"></div>
            </div>
            <div>
            <img src="<?= $ladybug_img ?>">
            <!-- ページ追加ボタン -->
            <form class="add_page basic" method="post" action="/views/page/create_page.php">
                <!--ワンタイムトークン発生-->
                <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                <button class="submit">ADD PAGE</button>
            </form>
            </div>
        </section>
        <!-- 既存ノートリスト -->
        <section class="note_list exist_notes">
            <?php foreach($note_list as $n_id => $n_info): ?>
                <button class="note <?= $n_info['color'] ?>" value="<?= $n_id ?>">
                    <div class="note_base"></div>
                    <div class="note_title">
                        <p><?= $n_info['note_title'] ?></p>
                    </div>
                    <div class="back_cover"></div>
                </button>
            <?php endforeach?>
        </section>
        <!-- 選択ノートメニュー -->
        <section class="selected selected_note">
            <div class="note">
                <div class="note_base"></div>
                <div class="note_title">
                    <p></p>
                </div>
                <div class="back_cover"></div>
            </div>
            <div class="selected_menu">
                <!-- ノート編集ボタン -->
                <button class="edit_btn">
                        <svg class="delete_icon" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300"><?=Icons::EDIT ?></svg>
                </button>
                <!-- ノート削除ボタン -->
                <form class="delete" method="post" action="/controllers/note/delete_note.php">
                    <!--ワンタイムトークン発生-->
                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                    <input type="hidden" name="note_id" class="set_note_id" value="">
                    <button class="delete_btn">
                        <svg class="delete_icon" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300"><?=Icons::DELETE ?></svg>
                    </button>
                </form>
            </button>   
        </section> 
        <!-- チャプターリスト -->
        <section class="chapter_list">
            <!-- <button class="chapter">
                <p></p>
                <input type="hidden" name="page_type" value="">
            </button> -->
        </section>
        <!-- 選択チャプターメニュー -->
        <section class="selected selected_chapter">
            <div class="note">
                <div class="note_base"></div>
                <div class="note_title">
                    <p></p>
                </div>
                <div class="back_cover"></div>
            </div>
            <div class="chapter">
                <p></p>
            </div>
            <div class="selected_menu">
                <!-- チャプター編集ボタン -->
                <button class="edit_btn">
                    <svg class="delete_icon" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300"><?=Icons::EDIT ?></svg>
                </button>
                <!-- チャプター削除ボタン -->
                <form class="delete" method="post" action="/controllers/chapter/delete_chapter.php">
                    <!--ワンタイムトークン発生-->
                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                    <input type="hidden" name="chapter_id" class="set_chapter_id" value="">
                    <button class="delete_btn">
                        <svg class="delete_icon" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 300 300"><?=Icons::DELETE ?></svg>
                    </button>
                </form>
            </button>
        </section> 
        <form class="page_list" method="post" action="">
			<!--ワンタイムトークン発生-->
			<input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
			<input class="set_note_color" type="hidden" name="color">
            <!-- <button class="page">
                <div class="wrapback"></div>
                <p>page's icon here's title</p>
            </button> -->
        </form>
    </div>

    <section class="modal_section">
        <!-- モーダル背景 -->
        <div class="modal_back"></div>
        <!-- note編集モーダル -->
        <div class="note_modal card">
            <button class="close_icon close_note_modal">×</button>
            <form method="post" action="/controllers/note/edit_note_check.php">
                <!--ワンタイムトークン発生-->
                <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                <input type="hidden" name="note_id" class="set_note_id" value="">
                <h3>Change Color?</h3>
                <div class="note_list color_lineup">
                    <?php foreach($note_colors as $color):?>
                        <label for="<?= $color ?>" class="note_icon note_<?= $color ?>">
                            <input type="radio" id="<?= $color ?>" name="color" value="<?= $color ?>">
                            <div class="note <?= $color ?>">
                                <div class="note_base"></div>
                                <div class="note_title">
                                    <p><?= $color ?></p>
                                </div>
                                <div class="back_cover"></div>
                            </div>
                        </label>
                    <?php endforeach ?>
                </div>
                <h3>Edit Note Title</h3>
                <textarea class="edit_title" name="note_title"></textarea>
                <button type="submit" class="send">EDIT</button>
            </form>
        </div>
        <div class ="chapter_modal card">
            <button class="close_icon close_chapter_modal">×</button>
            <form method="post" action="/controllers/chapter/edit_chapter_check.php">
                <!--ワンタイムトークン発生-->
                <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                <input type="hidden" name="chapter_id" class="set_chapter_id" value="">
                <h3>Edit Chapter Title</h3>
                <textarea class="edit_title" name="chapter_title"></textarea>
                <button type="submit" class="send">EDIT</button>
            </form>
        </div>
    </section>

    <!-- jQurery -->
    <script src="/public/js/inclusion.js" type="text/javascript"></script>
    <script src="/public/js/mem_top.js" type="text/javascript"></script>
</body>
</html>