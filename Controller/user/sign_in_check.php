<?php

include(dirname(__FILE__, 2).'/common/redirect.php');

//必要ファイル呼び出し
require_once(dirname(__FILE__, 2).'/Model/Connect.php');
require_once(dirname(__FILE__, 2).'/Model/Users.php');

//エラ〜メッセージを空にする
$_SESSION['msg'] = array();

//ワンタイムトークンチェック
validToken();

//3回以上エラーしてたらログイン不可
if(isset($_SESSION['error_back_count'])&& $_SESSION['error_back_count']>=3){
	$_SESSION['msg'] = ['error' => [Config::MSG_USER_LOGIN_TRYTIMES_OVER]];
	header('Location:../Controller/user/sign_in.php');
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
            $_SESSION['msg'] = ['error' => ['メールアドレス もしくは パスワードに誤りがあります。']];
            $_SESSION['error_back_count'] ++;
            header('Location: ../Views/user/sign_in.php');
            exit;
    }
    if($email==$user_info['email'] && password_verify($pass, $user_info['pass'])==true){
            $_SESSION['user_info'] = array('nick_name'=>$user_info['nick_name'], 'user_id'=>$user_info['user_id']);
            $_SESSION['msg'] = array();
            $_SESSION['error_back_count'] = 0;
            echo '<input type="hidden" name="token" value="'. SaftyUtil::generateToken() .'">';
            header('Location: ../Views/user/user_top.php');
            exit;
        }

}catch(Exception $e){
	$_SESSION['error_back_count'] ++;
	catchException();
}

?>