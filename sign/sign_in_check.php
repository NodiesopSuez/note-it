<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
 require_once __DIR__ . '/class/db/Connect.php';
 require_once __DIR__ . '/class/db/Users.php';
 require_once __DIR__ . '/class/config/Config.php';
 require_once __DIR__ . '/class/util/Utility.php';

//エラ〜メッセージを空にする
$_SESSION['error'] = array();

//ワンタイムトークンチェック
if(!SaftyUtil::validToken($_SESSION['token'])){
	$_SESSION['error'][] = Config::MSG_INVALID_PROCESS;
	header('Location:../sign/sign_in.php');
	exit;
}
//3回以上エラーしてたらログイン不可
if(isset($_SESSION['error_back_count'])&& $_SESSION['error_back_count']>=3){
	$_SESSION['error'][] = Config::MSG_USER_LOGIN_TRYTIMES_OVER;
	header('Location:../sign/sign_in.php');
	exit;
}

//入力内容をサニタイズ
$entered_data = saftyUtil::sanitize(2,$_POST);
$email = $entered_data['email'];
$pass = $entered_data['pass'];

try{
    $db = new Users;

    //メールアドレスからユーザー情報を検索
    $category = 'email';
    $user_info = $db->findUserinfo($email, $category);
    $db = null;

    if($email=='' || $pass=='' || 
        empty($user_info) || $email!==$user_info['email'] ||
        password_verify($pass, $user_info['pass'])==false){
            $_SESSION['error'][] = 'メールアドレス もしくは パスワードに誤りがあります。';
            $_SESSION['error_back_count'] ++;
            header('Location: ../sign/sign_in.php');
            exit;
    }
    if($email==$user_info['email'] && password_verify($pass, $user_info['pass'])==true){
            $_SESSION['user_info'] = array('nick_name'=>$user_info['nick_name'], 'user_id'=>$user_info['user_id']);
            $_SESSION['error'] = array();
            $_SESSION['error_back_count'] = 0;
            echo '<input type="hidden" name="token" value="'. SaftyUtil::generateToken() .'">';
            header('Location: ../mem/mem_top.php');
            exit;
        }

}catch(Exception $e){
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
	$_SESSION['error_back_count'] ++;
	header('Location:../sign/sign_in.php');
}

?>