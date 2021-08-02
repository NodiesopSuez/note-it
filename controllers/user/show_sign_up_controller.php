<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

$email = '';
$nick_name = '';
$birth = '';
$gender= '';
$ladybug = '/public/img/ladybug_nm.png';
$msg = ['情報を入力してください。'];

//エラーあるか
if(!empty($_SESSION['msg']['error'])){
    $ladybug = '/public/img/ladybug_sd.png';
    extract($_SESSION['data']);
    $msg = $_SESSION['msg']['error'];
}

$show_msg = count($msg)>=2 ? implode("<br/>", $msg) : $msg[0];

//前回入力時の値を表示
function showPrevContents($contents){
    if(!empty($contents)){
        echo 'value="'.$contents.'"';
    }
}
function showPrevChoice($choice){
    if(!empty($gender) && $gender==$choice){
        echo 'checked="checked"';
    }
}

?>