<?php

include(dirname(__FILE__, 2).'/common/redirect.php');

require_once(dirname(__FILE__, 2).'/class/db/Users.php');
require_once(dirname(__FILE__, 2).'/class/db/Searches.php');
require_once(dirname(__FILE__, 2).'/class/config/Icons.php');



cannnotAuthenticate();

try {
    //user_idからノート情報検索
    extract($_SESSION['user_info']);
    $search = new Searches;
    $note_list = $search->findNoteInfo('user_id', $user_id );

    $utility = new SaftyUtil;
    foreach ($note_list as $key => $val) {
        $note_list[$key] = $utility->sanitize(2, $val);
    }
    $search = null;

    $user_id   = $_SESSION['user_info']['user_id'];
    $nick_name = $_SESSION['user_info']['nick_name'];

    //現在の日本時刻を取得 >> 変数に分割
    date_default_timezone_set('Asia/Tokyo');
    $now_dt = getDate();
    extract($now_dt);
 
    if (empty($_SESSION['msg'])) {
        $ladybug_img = '../public/img/ladybug_nm.png';
        if ($hours>=5 && $hours<12) {
            $msg = array('おはようございます!　'.$nick_name.'さん!');
        } elseif ($hours>=12 && $hours<17) {
            $msg = array('こんにちは!　'.$nick_name.'さん!');
        } elseif (($hours>=17 && $hours<=23) || ($hours>=0 && $hours<5)) {
            $msg = array('ヤァこんばんは!　'.$nick_name.'さん!');
        }
    } elseif (!empty($_SESSION['msg']['error'])) {
        $ladybug_img = '../public/img/ladybug_sd.png';
        $msg = $_SESSION['msg']['error'];
    } elseif (!empty($_SESSION['msg']['okmsg'])) {
        $ladybug_img = '../public/img/ladybug_nm.png';
        $msg = $_SESSION['msg']['okmsg'];
    }
    $show_msg = count($msg)>=2 ? implode("<br/>", $msg) : $msg[0];
}catch(Exception $e){
    catchException();
}

$_SESSION['msg'] = array();
$color = 'basic'; //ヘッダーメニューのカラークラス
$note_colors = ['blue', 'pink', 'purple', 'yellow', 'green'];

?>
