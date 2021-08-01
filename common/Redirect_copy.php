<?php

class Redirect {
    public function __construct(){
        //セッションスタート
        session_start();
        session_regenerate_id();

        //必要ファイル呼び出し
        require_once(dirname(__FILE__, 2).'/config/Config.php');
        require_once(dirname(__FILE__, 2).'/util/Utility.php');
    }

    //ログインしてなければログイン画面に
    public function cannnotAuthenticate(){
        if (empty($_SESSION['user_info'])) {
            header('Location: ../Views/user/sign_in.php');
            exit;
        }
    }
    
    //ワンタイムトークンチェック
    public function validToken(){
        if(!SaftyUtil::validToken($_SESSION['token'])){
            $_SESSION['msg'] = ['error' => [Config::MSG_INVALID_PROCESS]];
            header('Location: ../Views/user/sign_in.php');
            exit;
        }
    }
    
    //例外キャッチしたら
    public function catchException(){
        $_SESSION['msg'] = ['error' => [Config::MSG_EXCEPTION]];
        header('Location:../Views/user/mem_top.php');
        exit;
    }

}



?>