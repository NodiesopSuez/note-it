<?php
class Deletes extends Connect
{
    //dsn
    public function __construct()
    {
        parent::__construct();
    }

    public function removeFiles(string $designation, $id_list):bool{
        if($designation === 'note_chapter'){
            $sql = "SELECT * FROM page_b_contents 
                    INNER JOIN page_info ON page_b_contents.page_id = page_info.page_id 
                    WHERE page_info.chapter_id IN (" . $id_list .")";
        }elseif($designation === 'page'){
            $sql = "SELECT * FROM pageb_contents WHERE page_id = ". $id_list ;
        }
        
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $get_info = $stmt->FetchAll(PDO::FETCH_ASSOC);

        foreach($get_info as $info){
            if($info['file_type'] === 'img'){
                $unlink_bool[] = unlink($info['data']);
            }
        }

        $bool = in_array(0, $unlink_bool) ? false : true ;
        return $bool;
    }
}

?>