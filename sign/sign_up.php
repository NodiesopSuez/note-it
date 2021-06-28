<?php
ini_set('session.save_handler', 'memcached');
ini_set('session.save_path', getenv('MEMCACHIER_SERVERS'));
if(version_compare(phpversion('memcached'), '3', '>=')) {
    ini_set('memcached.sess_persistent', 1);
    ini_set('memcached.sess_binary_protocol', 1);
} else {
    ini_set('session.save_path', 'PERSISTENT=myapp_session ' . ini_get('session.save_path'));
    ini_set('memcached.sess_binary', 1);
}
ini_set('memcached.sess_sasl_username', getenv('MEMCACHIER_USERNAME'));
ini_set('memcached.sess_sasl_password', getenv('MEMCACHIER_PASSWORD'));

session_start();
session_regenerate_id();
 
var_dump($_SESSION);

//外部ファイル読込
require_once('../class/config/Config.php');
require_once('../class/util/Utility.php');

$email = '';
$nick_name = '';
$birth = '';
$gender= '';
$ladybug = './img/ladybug_nm.png';
$msg = ['情報を入力してください。'];

//エラーあるか
if(!empty($_SESSION['error'])){
    $ladybug = './img/ladybug_sd.png';
    extract($_SESSION['data']);
    $msg = $_SESSION['error'];
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

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include('../head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="../main/color_template.css">
    <link rel="stylesheet" type="text/css" href="./sign_up.css" media="screen and (min-width:1024px)">
    <link rel="stylesheet" type="text/css" href="../inclusion/top_header.css">
</head>
<body>
    <div class="container">
    <?php include('../inclusion/top_header.php')?>
        <div class="ladybug">
            <div class="balloon">
                <div class="msg">
                    <?= $show_msg ?>
                </div>
                <div class="tail"></div>
            </div>
            <img src="<?= $ladybug ?>">
        </div>
        <form method="post" action="../sign/sign_up_check.php" class="basic">
            <!-- ワンタイムトークン発生 -->
            <input type="hidden" name="token" value="<?= SaftyUtil::generateToken()?>">
            <div class="form-group text">
                <label>email</label>
                <input type="text" name="email" class="form-controll" <?php showPrevContents($email)?>>
            </div>
            <div class="form-group text">
                <label>name</label>
                <input type="text" name="nick_name" class="form-controll" <?php showPrevContents($nick_name)?>>
            </div>
            <div class="form-group gender">
                <label>gender</label>
                <ul>
                    <li><input type="radio" name="gender" value="male">男性</li>
                    <li><input type="radio" name="gender" value="female">女性</li>
                    <li><input type="radio" name="gender" value="other">その他</li>
                </ul>
            </div>
            <div class="form-group text">
                <label>birth</label>
                <input type="date" name="birth" class="form-controll" <?php showPrevContents($birth)?>>
            </div>
            <div class="form-group text">
                <label>password</label>
                <input type="password" name="pass" class="form-controll">
            </div>
            <div class="form-group text">
                <label>password(再度ご入力ください)</label>
                <input type="password" name="pass2" class="form-controll">
            </div>

            <button type="submit" class="submit">confirm</button>
        </form>
    </div>
    <!-- <script src="../inclusion/inclusion.js" type="text/javascript"></script> -->
</body>
</html>

