<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

require_once(dirname(__FILE__, 3).'/config/Config.php');
require_once(dirname(__FILE__, 3).'/config/Connect.php');
require_once(dirname(__FILE__, 3).'/models/Searches.php');

try {
    header('Content-Type: application/json; charset=UTF-8');
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
        
        $chapter_id = $_POST['selected_chapter_id'];

        $search = new Searches;
        $page_list = $search->findPageInfo($chapter_id);

        echo json_encode($page_list);

        $search = null;
    }
}catch(Exception $e){
    catchException();
}

?>