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
$_SESSION['view_page'] = array();

/* //ログインしてなければログイン画面に
if(empty($_SESSION['user_info'])){
    header('Location: ../sign/sign_in.php');
    exit;
}

//ワンタイムトークンチェック
if(!SaftyUtil::validToken($_SESSION['token'])){
	$_SESSION['error'][] = Config::MSG_INVALID_PROCESS;
	header('Location: ../sign/sign_in.php');
	exit;
} */

//user_idからノート情報検索
extract($_SESSION['user_info']);
$search = new Searches;
$note_list = $search->findNoteInfo( 4/* $user_id */);
$search = null;

print_r($note_list);

//現在の日本時刻を取得 >> 変数に分割
date_default_timezone_set('Asia/Tokyo');
$now_dt = getDate();
extract($now_dt);

if (empty($_SESSION['error']) && empty($_SESSION['okmsg'])) {
	$ladybug_img = './img/ladybug_nm.png';
    if ($hours>=5 && $hours<12) {
		$msg = array('おはようございます!　'.$nick_name.'さん!');
    } elseif ($hours>=12 && $hours<17) {
        $msg = array('こんにちは!　'.$nick_name.'さん!');
    } elseif (($hours>=17 && $hours<=23) || ($hours>=0 && $hours<5)) {
        $msg = array('こんばんは!　'.$nick_name.'さん!');
    }
}elseif(!empty($_SESSION['error'])){
	$ladybug_img = './img/ladybug_sd.png';
	$msg[] = $_SESSION['error'];
}elseif(!empty($_SESSION['okmsg'])){
	$ladybug_img = './img/ladybug_nm.png';
	$msg[] = $_SESSION['okmsg'];
	$_SESSION['okmsg'] = array();
}

$_SESSION['add_ok'] = array();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include('../head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="./mem_top.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../inclusion/mem_header.css" media="screen and (min-width:1024px)">
</head>
<body>
    <div class="container">
    <?php include('../inclusion/mem_header.php')?>
        <section class="ladybug">
            <img src="<?= $ladybug_img ?>">
            <div class="balloon">
                <?php foreach($msg as $m) :?>
                    <?=  $m ?><br/>
                <?php endforeach ?>
            </div>
            <form class="add_page" method="post" action="">
                <!--ワンタイムトークン発生-->
                <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                <button class="add_btn">ADD PAGE</button>
            </form>
        </section>
        <section class="note_list">
            <?php foreach($note_list as $n_id => $n_info): ?>
                <button class="note <?= $n_info['color'] ?>" value="<?= $n_id ?>">
                    <div>
                        <p><?= $n_info['n_title'] ?></p>
                    </div>
                </button>
            <?php endforeach?>
        </section>
        <section class="selected_note">
            <div class="note">
                <div>
                    <p></p>
                </div>
            </div>
            <div class="selected_menu">
                <form class="edit" method="post" action="../note/edit_note.php">
                    <!--ワンタイムトークン発生-->
                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                    <input type="hidden" name="set_note_id" value=" note_id ">
                    <button class="edit_btn">EDIT</button>
                </form>
                <form class="delete" method="post" action="../note/delete_note.php">
                    <!--ワンタイムトークン発生-->
                    <input type="hidden" name="token" value="<?= SaftyUtil::generateToken() ?>">
                    <input type="hidden" name="set_note_id" value=" note_id ">
                    <button class="delete_btn">DELETE</button>
                </form>
            </button>
            
        </selected> 
        <section class="chapter_list">
            <button class="chapter">
                <p>chapter</p>
            </button>
            <button class="chapter">
                <p>chapter</p>
            </button>
            <button class="chapter">
                <p>chapter</p>
            </button>
            <button class="chapter">
                <p>chapter</p>
            </button>
            <button class="chapter">
                <p>chapter</p>
            </button>
            <button class="chapter">
                <p>chapter</p>
            </button>
        </section>
        <section class="page_list">
            <button class="page">
                <div class="wrapback"></div>
                <p>page's icon here's title</p>
            </button>
            <button class="page">
                <div class="wrapback"></div>
                <p>page's icon here's title</p>
            </button>
            <button class="page">
                <div class="wrapback"></div>
                <p>page's icon here's title</p>
            </button>
            <button class="page">
                <div class="wrapback"></div>
                <p>page's icon here's title</p>
            </button>
            <button class="page">
                <div class="wrapback"></div>
                <p>page's icon here's title</p>
            </button>
            <button class="page">
                <div class="wrapback"></div>
                <p>page's icon here's title</p>
            </button>
        </section>
        
    </div>
</body>
</html>