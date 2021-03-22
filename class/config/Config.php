<?php
    class Config
    {   
        //エラーメッセージ
        //ワンタイムトークン一致エラー
        const MSG_INVALID_PROCESS = '不正な処理が行われました。';
        
        //例外がスローされた時
        const MSG_EXCEPTION  = '申し訳ございません。エラーが発生しました。';
        
        //ユーザーデータが重複している
        const MSG_USER_DUPLICATE = '既に同じメールアドレスが登録されています。';
        
        //ログイン失敗
        const MSG_USER_LOGIN_FAILURE = 'メールアドレス もしくは パスワードに誤りがあります。';
        
        //ログイン試行回数オーバー
        const MSG_USER_LOGIN_TRYTIMES_OVER = 'ログインできません。<br/>しばらくしてから再度ログインしてください。';
        
        //アップロード失敗
        const MSG_UPLOAD_FAILURE = 'アップロードできませんできませんでした。';
        
        //登録時メッセージ
        //会員登録
        const TITLE_SIGNUP_SUCCESS = 'ご登録ありがとうございます！';
        const MSG_SIGNUP_SUCCESS = '登録を完了しました!!<br/>ログインしてください';
    }
?>