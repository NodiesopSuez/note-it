<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/config/Icons.php');
require_once('../class/util/Utility.php');

$msg =['追加するノートを選んでください。'];
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
            <div class="note_section">
                <div>
                    <!-- 新規か既存か -->
                    <div class="new_or_exist">
                        <button role="button" class="note new_note">
                                <div>
                                    <p>new</p>
                                </div>
                        </button>
                        <button role="button" class="note ex_note">
                                <div>
                                    <p>exist</p>
                                </div>
                        </button>
                    </div>
                    <!-- 新規：カラー選択 -->
                    <div class="note_color">
                        <button role="button" class="note">
                            <div>
                                <p>blue</p>
                            </div>
                        </button>
                        <button role="button" class="note">
                            <div>
                                <p>pink</p>
                            </div>
                        </button>
                        <button role="button" class="note">
                            <div>
                                <p>yellow</p>
                            </div>
                        </button>
                        <button role="button" class="note">
                            <div>
                                <p>green</p>
                            </div>
                        </button>
                        <button role="button" class="note">
                            <div>
                                <p>pruple</p>
                            </div>
                        </button>
                    </div>
                    <!-- 新規：ノート名入力フォーム -->
                    <div class="new_note_form">
                        <div class="note">
                            <div>
                                <input type="text" name="new_note_title" placeholder="ノート名"></input>
                            </div>
                        </div>
                        <button role="button" class="note">
                            <div>
                                <p>exist</p>
                            </div>
                        </button>
                    </div>
                </div>
                <!-- 既存：ノート選択 -->
                <div class="ex_note_list">
                    <button role="button" class="note">
                        <div>
                            <p>既存ノート</p>
                        </div>
                    </button>
                    <button role="button" class="note">
                        <div>
                            <p>new</p>
                        </div>
                    </button>
                </div>
            </div>
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