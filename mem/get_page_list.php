<?php
require_once('../class/config/Config.php');
require_once('../class/db/Connect.php');
require_once('../class/db/Searches.php');

try {
    header('Content-Type: application/json; charset=UTF-8');
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
        
        $chapter_id = $_POST['selected_chapter_id'];

        $search = new Searches;
        $page_list = $serach->findPageInfo($chapter_id);

        echo json_encode($chapter_list);

        $search = null;
    }
}catch(Exception $e){
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
	header('Location:../mem/sign_in.php');
}

?>