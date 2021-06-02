<?php
class Deletes extends Connect
{
    //dsn
    public function __construct()
    {
        parent::__construct();
    }

    public function removeFiles(string $designation, string $file_list):bool{
        if($designation ==='note_chapter'){
            $sql = "SELECT * FROM page_b_contents 
                    INNER JOIN page_info ON page_b_contents.page_id = page_info.page_id 
                    WHERE page_info.chapter_id IN (" . $file_list .")";
        } 
        $bool = true;
        return $bool;
    }
}

?>