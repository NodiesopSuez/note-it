<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

//ワンタイムトークンチェック
validToken();

require_once(dirname(__FILE__, 3).'/config/Connect.php');
require_once(dirname(__FILE__, 3).'/models/Users.php');


//エラー削除
if(!empty($_SESSION['msg']['error'])){
    $_SESSION = array();
}

try{
//受け取った情報を変数に代入
$_SESSION['data'] = $_POST;
extract($_SESSION['data']);

//入力されたメールアドレスでユーザ情報検索
$users = new Users;
$category = 'email';
$user_info = $users->findUserInfo($email, $category);


//メールアドレス NG >> 空欄・半角でない・@入力ない・同一アドレス存在
if($email == '' || mb_ereg_match('^(\s|　)+$',$email)){
	$_SESSION['msg'] = ['error' => ['メールアドレスを入力してください。']];
}elseif(preg_match("|^[a-z0-9_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$|",$email)==false){
	$_SESSION['msg'] = ['error' => ['メールアドレスを正しく入力してください。']];
}elseif(!empty($user_info)){
	$_SESSION['msg'] = ['error' => ['既に同一アドレスで登録されています。']];
}

//ネーム NG >> 空欄
if($nick_name == '' || mb_ereg_match('^(\s|　)+$',$nick_name)){
	$_SESSION['msg'] = ['error' => ['ニックネームを入力してください。']];
}

//性別 NG >> 未選択
if(!isset($gender)){
    $_SESSION['msg'] = ['error' => ['性別を選んでください。']];
}

//生年月日 NG >> 未選択
if($birth == '' || mb_ereg_match('^(\s|　)+$',$birth)){
	$_SESSION['msg'] = ['error' => ['生年月日を入力してください。']];
}

//パスワード NG >> 1回目空欄・2回目空欄・不一致・半角もしくは8文字以上でない
if($pass == '' || mb_ereg_match('^(\s|　)+$',$pass)){
	$_SESSION['msg'] = ['error' => ['パスワードを入力してください。']];
}elseif($pass2 == '' || mb_ereg_match('^(\s|　)+$',$pass2)){
	$_SESSION['msg'] = ['error' => ['2回目のパスワードを入力してください。']];
}elseif($pass !== $pass2){
	$_SESSION['msg'] = ['error' => ['パスワードが一致しません。']];
}

if(preg_match("/^[a-zA-z0-9]|[!\"#<=>&~@%+$\'\*\^\(\)\[\]\|\/\.,_-]+$/",$pass)==false
	|| preg_match("/^[a-zA-z0-9]|[!\"#<=>&~@%+$\'\*\^\(\)\[\]\|\/\.,_-]+$/",$pass2)==false
	|| strlen($pass)<8
	|| strlen($pass2)<8
	){
		$_SESSION['msg'] = ['error' => ['パスワードは半角英数字8桁以上で入力してください。']];
	}

$users = null;

//$_SESSIONにエラーメッセージが含まれていたら、登録画面に戻る
if(!empty($_SESSION['msg']['error'])){
	header('Location:/views/user/sign_up.php');
	exit;
}else{
	header('Location:/controllers/user/sign_up_done_controller.php');
	exit;
}

}catch(Exception $e){
	catchException();
}

?>