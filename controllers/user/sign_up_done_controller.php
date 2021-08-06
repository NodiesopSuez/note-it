<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

//外部ファイル読込
require_once(dirname(__FILE__, 3).'/config/Connect.php');
require_once(dirname(__FILE__, 3).'/models/Users.php');

//情報をサニタイズして変数に代入
$add_data = saftyUtil::sanitize(1,$_SESSION['data']);
$nick_name = $add_data['nick_name'];
$gender = $add_data['gender'];
$email = $add_data['email'];
$birth = $add_data['birth'];
$pass = password_hash($add_data['pass'],PASSWORD_DEFAULT);

//現在日時を変数に代入
$dt = new DateTime();
$jpn = $dt->setTimeZone(new DateTimeZone('Asia/Tokyo'));
$registration_dt = $jpn->format('Y-m-d H:i:s');

//入力内容が入った変数をデータベースに追加
try{	
	$register = new Users;
	$register->registerUserData($nick_name,$gender,$email,$birth,$pass,$registration_dt);
	
	if($register==true){
		unset($_SESSION['msg']);
		unset($_SESSION['data']);
		$_SESSION['msg']['okmsg'] = '登録を完了しました!!<br/>ログインしてください'; 
		header('Location:/views/user/sign_in.php');
	}
}catch(Exception $e){
	catchException();
}
?>