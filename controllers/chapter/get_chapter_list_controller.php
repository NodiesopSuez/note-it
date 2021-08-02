<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

require_once(dirname(__FILE__, 3).'/config/Connect.php');
require_once(dirname(__FILE__, 3).'/models/Searches.php');

try {
    header('Content-Type: application/json; charset=UTF-8');
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){

		$note_id = $_POST["selected_note_id"];
    
		$search = new Searches;
		$chapter_list = $search->findChapterInfo('note_id', $note_id);
		
		echo json_encode($chapter_list);

		$search = null;
	}
}catch(Exception $e){
    catchException();
}
?>



