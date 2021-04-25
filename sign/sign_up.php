<?php
session_start();
session_regenerate_id();

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
</head>
<body>
    <div class="container">
        <div class="ladybug">
            <img src="<?= $ladybug ?>">
            <div class="balloon">
                <?php foreach($msg as $m): ?>
                <?= $m ?><br/>
                <?php endforeach ?>
            </div>
        </div>
        <form method="post" action="sign_up_check.php">
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

            <button type="submit" class="send">confirm</button>
        </form>
    </div>
</body>
</html>

