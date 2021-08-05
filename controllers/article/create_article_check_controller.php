<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

authenticateError();
validToken();


//必要ファイル呼び出し
require_once(dirname(__FILE__, 3).'/config/Connect.php');
require_once(dirname(__FILE__, 3).'/models/Users.php');
require_once(dirname(__FILE__, 3).'/models/Searches.php');
require_once(dirname(__FILE__, 3).'/vendor/autoload.php');

unset($_SESSION['msg']);
unset($_SESSION['page']);

try {
    //存在を宣言しておく変数
    $user_id = $_SESSION['user_info']['user_id'];

    $search = new Searches;
    $utility = new SaftyUtil;

    $sanitized = $utility->sanitize(2, $_POST);
    
    $note_existence = $sanitized['note_existence'];
    $chapter_existence = $sanitized['chapter_existence'];

    //新規ノート作成の場合
    if($note_existence === 'new'){
        $note_list = $search->findNoteInfo('user_id', $user_id);

        if (empty($sanitized['new_note_title']) || ctype_space($sanitized['new_note_title'])) {
            $_SESSION['msg']['error'][] = 'ノートのタイトルを入力して下さい。';
        }
        if (in_array($sanitized['new_note_title'], $note_list)) {
            $_SESSION['msg']['error'][] = '既に同じノートが作成されています。';
        }
        if (empty($sanitized['note_color'])){
            $_SESSION['msg']['error'][] = 'ノートのカラーを選択してください。';
        }
    }

    //既存ノートに作成する場合
    if($note_existence === 'exist'){
        if(isset($sanitized['note_id'])){
            //チャプターリストを取得しておく
            $chapter_list = $search->findChapterInfo('note_id', $note_id);
        }elseif(!isset($sanitized['note_id']) || $sanitized['note_id'] === ''){
            $_SESSION['msg']['error'][] = 'ノートのタイトルを選択して下さい。'; 
        }
    }
    
    //新規チャプター作成の場合
    if($chapter_existence === 'new'){   
        if (!isset($sanitized['page_type']) || ($sanitized['page_type'] != 1 && $sanitized['page_type'] != 2)) {
            $_SESSION['msg']['error'][] = 'ページのタイプを選択して下さい。';
        }
        if (!isset($sanitized['new_chapter_title']) || $sanitized['new_chapter_title'] == "" || ctype_space($sanitized['new_chapter_title'])) {
            $_SESSION['msg']['error'][] = 'チャプターのタイトルを入力して下さい。';
        }
        if ($note_existence === 'exist' && in_array($sanitized['new_chapter_title'], $chapter_list)){
            $_SESSION['msg']['error'][] = '既にそのチャプターは作成されています。';
        }
    }

    //既存チャプターに作成する場合
    if(($chapter_existence === 'exist') && (!isset($sanitized['chapter_id']) || $sanitized['chapter_id'] === '')){
            $_SESSION['msg']['error'][] = 'チャプターを選択して下さい。';
    }

    //page_titleが入力されているか
    /* if((!isset($sanitized['page_title'])) || ($sanitized['page_title'] == "") || (ctype_space($sanitized['page_title']))){
        $_SESSION['msg']['error'][] = 'ページタイトルを入力して下さい。';
    }  */

    //$_SESSONにノート・チャプター情報を代入
    $_SESSION['page']['register_info'] = array(
        'note_existence'    => $note_existence,
        'note_title'        => $note_existence === 'new' ? $sanitized['new_note_title'] : null,
        'note_color'        => $note_existence === 'new' ? $sanitized['note_color'] : null,
        'note_id'           => $note_existence === 'exist' ? $sanitized['note_id'] : null,
        'chapter_existence' => $chapter_existence,
        'chapter_title'     => $chapter_existence === 'new' ? $sanitized['new_chapter_title'] : null ,
        'page_type'         => $sanitized['page_type'],
        'chapter_id'        => $chapter_existence === 'exist' ? $sanitized['chapter_id'] : null,
        'page_title'        => $sanitized['page_title'],
    );

    //page type B のコンテンツを一旦格納する配列を宣言
    $page_b_contents = array();

    if(isset($sanitized['page_type']) && $sanitized['page_type'] == 1){  //page_type Aの場合、
        //入力内容をサニタイズして$_SESSIONに格納
        $_SESSION['page']['register_contents'] = [
            'meaning'  => $sanitized['meaning'],
            'syntax'   => $sanitized['syntax'], 
            'syn_memo' => $sanitized['syn_memo'],
            'example'  => $sanitized['example'], 
            'ex_memo'  => $sanitized['ex_memo'], 
            'memo'     => $sanitized['memo'], 
        ];
        
    }elseif(isset($sanitized['page_type']) && $sanitized['page_type'] == 2){  //page_type Bの場合、
        //キー名が'contents_'で始まるtextの内容とfile_type=textを格納
        foreach($sanitized as $key => $val){
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
                
                $img_path = htmlspecialchars($upload->get('ObjectURL'));

                //ファイルパスとfile_type=imgを格納
                $page_b_contents[$key]['file_type'] = $utility->sanitize(3, 'img');
                $page_b_contents[$key]['data']      = $utility->sanitize(3, $img_path);
            }
        }

        if(!empty($page_b_contents)){
            ksort($page_b_contents); //コンテンツを昇順に並べ替え
        }else{
            $_SESSION['msg']['error'][] = '本文を入力してください';
        }

        //入力内容を$_SESSIONに格納
        $_SESSION['page']['register_contents'] = $page_b_contents;
    }

    echo '$_POST<br/>';
    print_r($_sanitized);
    //echo '<br/>'.$page_title. '<br/>';
    echo '<br/>';
    print_r($_SESSION);
    
    $search = null;

    if(!empty($_SESSION['msg'])){
        header('Location:/views/article/create_article.php'); //エラーがあったら入力ページに戻る
    }else{
        header('Location:/controllers/article/create_article_check_controller.php');
    }

}catch(Exception $e){
    catchException();
}

?>