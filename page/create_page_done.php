<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/util/Utility.php');
require_once('../class/db/Connect.php');
require_once('../class/db/Users.php');
require_once('../class/db/Additions.php');

//ログインしてなければログイン画面へ
/* if(empty($_SESSION['user_data'])){
    header('Location:../sign/sign_in.php');
} */

//ユーザー情報
$user_id = 4;//$_SESSION['user_info']['user_id'];

//登録情報をサニタイズ
$add_contents = $_SESSION['add_contents'];
var_dump($_SESSION);

foreach($add_contents as $key => $val){
    $add_contents[$key] = htmlspecialchars($val, ENT_QUOTES, "UTF-8");
}
extract($add_contents);


?>
