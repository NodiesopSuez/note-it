<?php
    class Config
    {   
        //エラーメッセージ
        //ワンタイムトークン一致エラー
        const MSG_INVALID_PROCESS = '不正な処理が行われました。';
        
        //例外がスローされた時
        const MSG_EXCEPTION  = '申し訳ございません。<br/>エラーが発生しました。';
        
        //ログイン試行回数オーバー
        const MSG_USER_LOGIN_TRYTIMES_OVER = 'ログインできません。<br/>しばらくしてから再度ログインしてください。';
        
        //アップロード失敗
        const MSG_UPLOAD_FAILURE = 'アップロードできませんできませんでした。';

    }
?>