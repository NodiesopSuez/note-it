<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

authenticateError();
validToken();

//必要ファイル呼び出し
require_once(dirname(__FILE__, 3).'/config/Connect.php');
require_once(dirname(__FILE__, 3).'/models/Users.php');
require_once(dirname(__FILE__, 3).'/models/Searches.php');
require_once(dirname(__FILE__, 3).'/vendor/autoload.php');

//エラー・前回の入力残ってたら削除
if(!empty($_SESSION['msg']['error'])){
    $_SESSION['msg'] = array();
}

try {
    //存在を宣言しておく変数
    $chapter_existence = null;
    $note_id = null;

    $search = new Searches;
    $utility = new SaftyUtil;
    
    $sanitized = $utility->sanitize(1, $_POST); //$_POSTにはtextのデータ
    extract($sanitized);  //POSTで受け取った配列を変数にする

   /*  echo '<br/>$_SESSION[contetns]<br/>';
    print_r($_SESSION['page']); 
    echo '<br/>$_FILES<br/>';  */
    /*var_dump($_FILES);
    echo '<br/>$sanitized<br/>'; 
    var_dump($sanitized);
    echo '<br/><br/>';  */

    //page_titleが入力されているか
    if(empty($page_title) || ctype_space($page_title)){
        $_SESSION['msg'] = ['error' => ['ページタイトルを入力してください。']];
    }

    //ページ情報
    $_SESSION['page']['page_title'] = $page_title;

    if(!empty($_SESSION['page']['page_type']) && $_SESSION['page']['page_type'] === 1){  //page_type Aの場合、
        echo 'ここはきてる<br/>';
        //入力内容をサニタイズして$_SESSIONに格納
        $_SESSION['page'][] = ['update_contents' => [ 'meaning'   => $meaning,
                                                    'syntax'    => $syntax, 
                                                    'syn_memo'  => $syn_memo,
                                                    'example'   => $example, 
                                                    'ex_memo'   => $ex_memo, 
                                                    'memo'      => $memo        ]];
        
    }elseif(!empty($_SESSION['page']['page_type']) && $_SESSION['page']['page_type'] === 2){  //page_type Bの場合、
        //page type B のコンテンツを一旦格納する配列を宣言
        $page_b_contents = array();
        $remove_objects  = array();
        
        //キー名が'contents_'で始まるtextの内容とfile_type=textを格納
        //$_SESSION['contents']から該当キー削除
        foreach($sanitized as $key => $val){
            if(preg_match('/contents\_/',$key) === 1 && !empty($val)){
                $page_b_contents[$key] = [ 'file_type' => 'text', 'data' => $val ];
                $_SESSION['contents'][$key] = array();
            }
        }

        //img
        $imgs = $_FILES;

        foreach($_SESSION['contents'] as $contents => $val){
            //$_FILESに存在しているかどうか
            if (array_key_exists($contents, $imgs) && $val['file_type'] === 'img') {
                if(!empty($imgs[$contents]['tmp_name'])){
                    $remove_objects[] = $val['data'];
                }else{
                    $page_b_contents[$contents]['file_type'] = $utility->sanitize(3, 'img');
                    $page_b_contents[$contents]['data']      = $utility->sanitize(3, $val['data']);
                }
            }elseif(!array_key_exists($contents, $imgs) && $val['file_type'] === 'img'){
                $remove_objects[] = $val['data'];
            }
            
        }

        //AWS S3処理
        $s3 = new Aws\S3\S3Client([
            'version'  => 'latest',
            'region'   => 'ap-northeast-3',
        ]);
        $bucket = getenv('S3_BUCKET_NAME')?: die('No "S3_BUCKET" config var in found in env!');

        $keys = $s3->listObjects([
            'Bucket' => $bucket
        ]); 

        foreach ($remove_objects as $object) {
            $remove_keys[] = str_replace("https://noteit-contentsimg.s3.ap-northeast-3.amazonaws.com/", "", $object);
        }

        foreach($remove_keys as $key) {
            $s3->deleteObjects([
                'Bucket' => $bucket,
                'Delete' => [
                    'Objects' => [
                        ['Key' => $key]
                    ]                
                ]
            ]);
        }


        foreach($imgs as $key => $img){
            if($img['error'] === 0 && !empty($img['tmp_name'])){
                //ファイルの拡張子を求める
                $type      = strstr($img['type'], '/');
                $file_type = str_replace('/', '', $type);
                
                //ランダムな文字列でファイル名生成
                $img['name'] = uniqid(bin2hex(random_bytes(1))).'.'.$file_type;

                //ドキュメントでは
                $upload = $s3->upload($bucket, $img['name'], fopen($img['tmp_name'], 'rb'), 'public-read');
                
                $img_path = htmlspecialchars($upload->get('ObjectURL'));

                //ファイルパスとfile_type=imgを格納
                $page_b_contents[$key]['file_type'] = $utility->sanitize(3, 'img');
                $page_b_contents[$key]['data']      = $utility->sanitize(3, $img_path);
            }
        }

        if(!empty($page_b_contents)){
            ksort($page_b_contents); //コンテンツを昇順に並べ替え
        }else{
            $_SESSION['msg'] = ['error' => ['本文を入力してください。']];
        }

        //print_r($page_b_contents);
        //入力内容を$_SESSIONに格納
        unset($_SSESSION['contents']);
        $_SESSION['page']['update_contents'] = $page_b_contents;

    }
    
    $search  = null;
    $utility = null;
    
    if(!empty($_SESSION['msg']['error'])){
        header('Location:/views/article/edit_article_b.php'); //エラーがあったら入力ページに戻る
    }else{
        header('Location:/controllers/article/edit_article_done_controller.php');
    }

}catch(Exception $e){
    catchException();
}
?>