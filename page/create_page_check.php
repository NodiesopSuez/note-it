<?php
//セッションスタート
session_start();
session_regenerate_id();

//必要ファイル呼び出し
require_once('../class/config/Config.php');
require_once('../class/util/Utility.php');
require_once('../class/db/Connect.php');
require_once('../class/db/Users.php');
require_once('../class/db/Searches.php');

//print_r($_POST);
//print_r($_FILES);

//ログインしてなければログイン画面へ
if(empty($_SESSION['user_data'])){
    header('Location:../sign/sign_in.php');
}

$user_id = $_SESSION['user_data']['user_id'];


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
    extract($sanitized);  //POSTで受け取った配列を変数にする

    //新規ノート作成の場合
    if($note_existence === 'new'){
        $note_list = $search->findNoteInfo('user_id', $user_id);

        if (empty($new_note_title) || ctype_space($new_note_title)) {
            $_SESSION['error'][] = 'ノートのタイトルを入力してください';
        }
        if (in_array($new_note_title, $note_list)) {
            $_SESSION['error'][] = '既にそのノートは作成されています';
        }
        if (!isset($note_color) || empty($note_color)){
            $_SESSION['error'][] = 'ノートのカラーを選択してください';
        }
    }

    //既存ノートに作成する場合
    if($note_existence === 'exist'){
        if(isset($note_id)){
            //チャプターリストを取得しておく
            $chapter_list = $search->findChapterInfo('note_id', $note_id);
        }elseif(!isset($note_id) || $note_id = ''){
            $_SESSION['error'][] = 'ノートのタイトルを選択してください';    
        }
    }
    
    //新規チャプター作成の場合
    if($chapter_existence === 'new'){   
        if (!isset($page_type) || ($page_type != 1 && $page_type != 2)) {
            $_SESSION['error'][] = 'ページのタイプを選択してください';
        }
        if (empty($new_chapter_title) || ctype_space($new_chapter_title)) {
            $_SESSION['error'][] = 'チャプターのタイトルを入力してください';
        }
        if ($note_existence === 'exist' &&in_array($new_chapter_title, $chapter_list)){
            $_SESSION['error'][] = '既にそのチャプターは作成されています';
        }
    }

    //既存チャプターに作成する場合
    if((!isset($chapter_existence))
        ||($chapter_existence === 'exist' 
            && (!isset($chapter_id) || $chapter_id === ''))){
        $_SESSION['error'][] = 'チャプターを選択してください';
    }

    //page_titleが入力されているか
    if(empty($page_title) || ctype_space($page_title)){
        $_SESSION['error'][] = 'ページタイトルを入力してください';
    }

    //$_SESSONにノート・チャプター情報を代入
    $_SESSION['page']['register_info'] = array(
        'note_existence'    => $note_existence,
        'note_title'        => $note_existence === 'new' ? $new_note_title : null,
        'note_color'        => $note_existence === 'new' ? $note_color : null,
        'note_id'           => $note_existence === 'exist' ? $note_id : null,
        
        'chapter_existence' => $chapter_existence,
        'chapter_title'     => $chapter_existence === 'new' ? $new_chapter_title : null ,
        'page_type'         => $page_type,
        'chapter_id'        => $chapter_existence === 'exist' ? $chapter_id : null,

        'page_title'        => $page_title,
    );

    //page type B のコンテンツを一旦格納する配列を宣言
    $page_b_contents = array();

    if(isset($page_type) && $page_type == 1){  //page_type Aの場合、
        //入力内容をサニタイズして$_SESSIONに格納
        $_SESSION['page']['register_contents'] = [
            'meaning'  => $meaning,
            'syntax'   => $syntax, 
            'syn_memo' => $syn_memo,
            'example'  => $example, 
            'ex_memo'  => $ex_memo, 
            'memo'     => $memo, 
        ];
        
    }elseif(isset($page_type) && $page_type == 2){  //page_type Bの場合、
        //キー名が'contents_'で始まるtextの内容とfile_type=textを格納
        foreach($_POST as $key => $val){
            if(preg_match('/contents\_/',$key) === 1 && !empty($val)){
                $page_b_contents[$key]['file_type'] = 'text';
                $page_b_contents[$key]['data']      = $val;
            }
        }

        //imgファイルを
        $imgs = $_FILES;
        
        foreach($imgs as $key => $img){
            if($img['error'] === 0){
                //ファイルの拡張子を求める
                $type      = strstr($img['type'], '/');
                $file_type = str_replace('/', '', $type);
                //ランダムな文字列でファイル名生成
                $img['name'] = uniqid(bin2hex(random_bytes(1))).'.'.$file_type;
                $img_path    = '../page/contents_img/'.$img['name'];
                //tmp_fileをディレクトリに格納
                move_uploaded_file($img['tmp_name'], $img_path);
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
        $_SESSION['page']['register_contents'] = $page_b_contents;

    }
    
    $search = null;

    if(!empty($_SESSION['error'])){
        header('Location:../page/create_page.php'); //エラーがあったら入力ページに戻る
    }else{
        header('Location:../page/create_page_done.php');
    }

}catch(Exception $e){
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
    header('Location:../page/create_page.php');
    exit;
}

?>