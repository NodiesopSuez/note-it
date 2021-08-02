<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

authenticateError();
validToken();

require_once(dirname(__FILE__, 2).'/config/Connect.php');
require_once(dirname(__FILE__, 2).'/models/Searches.php');

if(!empty($_SESSION['page'])){
    extract($_SESSION['page']);
}else{
   catchException();
}

try {
    $search  = new Searches;
    $utility = new SaftyUtil;

    //ページとコンテンツ情報
    $get_page_info = $search->findPageContentsA($page_id);
    $page_info     = $utility->sanitize(2, $get_page_info);
    //チャプター情報
    $chapter_id   = $page_info['chapter_id'];
    $chapter_info = $search->findChapterInfo('chapter_id', $chapter_id);
    //ノート情報
    $note_id   = $chapter_info[$chapter_id]['note_id'];
    $note_info = $search->findNoteInfo('note_id', $note_id);

    $note_title = $utility->sanitize(4, $note_info[$note_id]['note_title']);
    $color      = $utility->sanitize(4, $note_info[$note_id]['color']);
    $chapter_title = $utility->sanitize(4, $chapter_info[$chapter_id]['chapter_title']);

    $search = null;

}catch(Exception $e){
    catchException();
}

?>