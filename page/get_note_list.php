<?php
 require_once(dirname(__FILE__, 2).'/class/config/Config.php');
 require_once(dirname(__FILE__, 2).'/class/db/Connect.php');
 require_once(dirname(__FILE__, 2).'/class/db/Searches.php');

try {
    header('Content-Type: application/json; charset=UTF-8');
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {

        $user_id = $_POST['user_id'];

        $search = new Searches;
        $note_list = $search->findNoteInfo('user_id', $user_id);

        echo json_encode($note_list);

        $search = null;
    }
}catch(Exception $e){
    $_SESSION['error'][] = Config::MSG_EXCEPTION;
	header('Location:../mem/sign_in.php');
}
?>