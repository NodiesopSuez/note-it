<?php

include(dirname(__FILE__, 3).'/common/redirect.php');

authenticateError();
validToken();

//必要ファイル呼び出し
require_once(dirname(__FILE__, 3).'/config/Connect.php');
require_once(dirname(__FILE__, 3).'/models/Searches.php');

if(!empty($_SESSION['page'])){
    extract($_SESSION['page']);
}else{
    catchException();
}

try {
    print_r($_SESSION['page']);

    
}catch(Exception $e){
    catchException();
}

?>
