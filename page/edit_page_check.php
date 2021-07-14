<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once(dirname(__FILE__, 2).'/class/config/Config.php');
require_once(dirname(__FILE__, 2).'/class/util/Utility.php');
require_once(dirname(__FILE__, 2).'/class/db/Connect.php');
require_once(dirname(__FILE__, 2).'/class/db/Users.php');
require_once(dirname(__FILE__, 2).'/class/db/Searches.php');
require_once(dirname(__FILE__, 2).'/vendor/autoload.php');

//print_r($_POST);
//print_r($_FILES);

//ログインしてなければログイン画面
if(empty($_SESSION['user_info'])){
    header('Location:../sign/sign_in.php');
}

//ワンタイムトークンチェック
if(!SaftyUtil::validToken($_SESSION['token'])){
    $_SESSION['error'][] = Config::MSG_INVALID_PROCESS;
    header('Location:../mem/mem_top.php');
    exit;
}

//エラー・前回の入力残ってたら削除
if(!empty($_SESSION['error'])){
    $_SESSION['error'] = array();
}

$_SESSION['page']  = array();

try {
    //存在を宣言しておく変数
    $chapter_existence = null;
    $note_id = null;

    $search = new Searches;
    $utility = new SaftyUtil;

    $sanitized = $utility->sanitize(1, $_POST);
    
    print_r($sanitized);
    echo '<br/>';
    print_r($_FILES);

    extract($sanitized);  //POSTで受け取った配列を変数にする

    //page_titleが入力されているか
    if(empty($page_title) || ctype_space($page_title)){
        $_SESSION['error'][] = 'ページタイトルを入力してください';
    }

    //page type B のコンテンツを一旦格納する配列を宣言
    $page_b_contents = array();

    if(isset($page_type) && $page_type == 1){  //page_type Aの場合、
        //入力内容をサニタイズして$_SESSIONに格納
        $_SESSION['page']['update_contents'] = [
            'page_id'   => $page_id,
            'page_title'=> $page_title,
            'page_type' => 1,
            'meaning'   => $meaning,
            'syntax'    => $syntax, 
            'syn_memo'  => $syn_memo,
            'example'   => $example, 
            'ex_memo'   => $ex_memo, 
            'memo'      => $memo, 
        ];
        
    }elseif(isset($page_type) && $page_type == 2){  //page_type Bの場合、
        $page_b_contents = ['page_type' => 2,
                            'page_id'   => $page_id,
                            'page_title'=> $page_title];
        //キー名が'contents_'で始まるtextの内容とfile_type=textを格納
        foreach($_POST as $key => $val){
            if(preg_match('/contents\_/',$key) === 1 && !empty($val)){
                $page_b_contents[$key]['file_type'] = 'text';
                $page_b_contents[$key]['data']      = $val;
            }
        }

        //imgファイルを
        $imgs = $_FILES;
        
        //AWS S3
        $s3 = new Aws\S3\S3Client([
            'version'  => 'latest',
            'region'   => 'ap-northeast-3',
        ]);
        $bucket = getenv('S3_BUCKET_NAME')?: die('No "S3_BUCKET" config var in found in env!');

        foreach($imgs as $key => $img){
            if($img['error'] === 0){
                //ファイルの拡張子を求める
                $type      = strstr($img['type'], '/');
                $file_type = str_replace('/', '', $type);
                
                //ランダムな文字列でファイル名生成
                $img['name'] = uniqid(bin2hex(random_bytes(1))).'.'.$file_type;

                //ドキュメントでは
                $upload = $s3->upload($bucket, $img['name'], fopen($img['tmp_name'], 'rb'), 'public-read');
                //記事では
                /* $upload = $s3->putObject([
                    'ACL' => 'public-read',
                    'Bucket' => $bucket,
                    'Key' => $img['name'],
                    'Body' => fopen($img['tmp_name'], 'rb'),
                    'ContentType' => mime_content_type($img['tmp_name']),
                ]); */
                
                $img_path = htmlspecialchars($upload->get('ObjectURL'));

                //ファイルパスとfile_type=imgを格納
                $page_b_contents[$key]['file_type'] = $utility->sanitize(3, 'img');
                $page_b_contents[$key]['data']      = $utility->sanitize(3, $img_path);
            }
        }

        if(!empty($page_b_contents)){
            ksort($page_b_contents); //コンテンツを昇順に並べ替え
        }else{
            $_SESSION['error'][] = '本文を入力してください';
        }
        //入力内容を$_SESSIONに格納
        $_SESSION['page']['update_contents'] = $page_b_contents;

    }
    
    $search = null;
    
    if(!empty($_SESSION['error'])){
        //header('Location:../mem/mem_top.php'); //エラーがあったら入力ページに戻る
    }else{
        //header('Location:../page/edit_page_done.php');
    }

}catch(Exception $e){
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
    //header('Location:../mem/mem_top.php');
    exit;
}
?>