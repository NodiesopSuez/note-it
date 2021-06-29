<?php
session_start();
session_regenerate_id();

//外部ファイル読込
 require_once(dirname(__FILE__, 2).'/class/db/Connect.php');
 require_once(dirname(__FILE__, 2).'/class/db/Users.php');
 require_once(dirname(__FILE__, 2).'/class/config/Config.php');
 require_once(dirname(__FILE__, 2).'/class/util/Utility.php');

print_r($_SESSION['data']);
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
		$_SESSION['error'] = array();
		$_SESSION['data'] = array();
		$_SESSION['okmsg'] = '登録を完了しました!!<br/>ログインしてください'; 
		header('Location:../sign/sign_in.php');
	}
}catch(Exception $e){
	$_SESSION['error'][] = Config::MSG_EXCEPTION;
	header('Location:../sign/sign_up.php');
}
?>