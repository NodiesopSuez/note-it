<?php
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/util/Utility.php');
require_once('../class/db/Connect.php');
require_once('../class/db/Users.php');

//ワンタイムトークンチェック
if(!SaftyUtil::validToken($_POST['token'])){
    $_SESSION['error'][] = Config::MSG_INVALID_PROCESS;
    header('Location:./sign_up.php');
    exit;
}

//エラー削除
if(!empty($_SESSION['error'])){
    $_SESSION = array();
}

try{
//受け取った情報を変数に代入
$_SESSION['data'] = $_POST;
print_r($_SESSION['data']);
extract($_SESSION['data']);

//入力されたメールアドレスでユーザ情報検索
$db = new Users;
$category = 'email';
$user_info = $db->findUserInfo($email, $category);
$db = null;

//メールアドレス NG >> 空欄・半角でない・@入力ない・同一アドレス存在
if($email == '' || mb_ereg_match('^(\s|　)+$',$email)){
	$_SESSION['error'][] = 'メールアドレスを入力してください。';
}elseif(preg_match("|^[a-z0-9_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$|",$email)==false){
	$_SESSION['error'][] = 'メールアドレスを正しく入力してください。';
}elseif(!empty($user_info)){
	$_SESSION['error'][] = '既に同一アドレスでのご登録がされています。';
}

//ネーム NG >> 空欄
if($nick_name == '' || mb_ereg_match('^(\s|　)+$',$nick_name)){
	$_SESSION['error'][] = 'ニックネームを入力してください。';
}

//性別 NG >> 未選択
if(!isset($gender)){
    $_SESSION['error'][] = '性別を選んでください。';
}

//生年月日 NG >> 未選択
if($birth == '' || mb_ereg_match('^(\s|　)+$',$birth)){
	$_SESSION['error'][] = '生年月日を入力してください。';
}

//パスワード NG >> 1回目空欄・2回目空欄・不一致・半角もしくは8文字以上でない
if($pass == '' || mb_ereg_match('^(\s|　)+$',$pass)){
	$_SESSION['error'][] = 'パスワードを入力してください。';
}elseif($pass2 == '' || mb_ereg_match('^(\s|　)+$',$pass2)){
	$_SESSION['error'][] = '２回目のパスワードを入力してください。';
}elseif($pass !== $pass2){
	$_SESSION['error'][] = 'パスワードが一致しません。';
}

if(
	preg_match("/^[a-zA-z0-9]|[!\"#<=>&~@%+$\'\*\^\(\)\[\]\|\/\.,_-]+$/",$pass)==false
	|| preg_match("/^[a-zA-z0-9]|[!\"#<=>&~@%+$\'\*\^\(\)\[\]\|\/\.,_-]+$/",$pass2)==false
	|| strlen($pass)<8
	|| strlen($pass2)<8
	){
		$_SESSION['error'][] = 'パスワードは半角英数字8桁以上で入力してください。';
	}

//$_SESSIONにエラーメッセージが含まれていたら、登録画面に戻る
if(!empty($_SESSION['error'])){
	header('Location:../sign/sign_up.php');
	exit;
}else{
	header('Location:../sign/sign_up_done.php');
	exit;
}

}catch(Exception $e){
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
	header('Location:../sign/sign_up.php');
	exit;
}

?>