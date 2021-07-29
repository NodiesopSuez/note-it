<?php
class Deletes extends Connect
{
    //dsn
    public function __construct()
    {
        parent::__construct();
    }

    //ノートを削除
    public function deleteNote(int $note_id):bool{
        $sql = "DELETE FROM note_info WHERE note_id = :note_id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue('note_id', $note_id, PDO::PARAM_INT);
        $bool = $stmt->execute();

        return $bool;
    }

    //チャプターを削除
    public function deleteChapter(string $designation, $id):bool{
        //$designation = 'note' or 'chapter'
        $sql = "DELETE FROM chapter_info WHERE ". $designation ."_id = ". $id;
        $stmt = $this->dbh->prepare($sql);
        $bool = $stmt->execute();

        return $bool;
    }



    //page_infoとpage_contentsから削除
    public function deletePageContents(string $designation, int $page_type, $id_list, string $forwhat='delete'){ 
        //ノート・チャプターから削除orページから削除 / どちらのページタイプで削除するか
        if ($designation === 'page') {
            if ($page_type === 1){
                $sql_contents = "DELETE FROM page_a_contents WHERE page_id = ". $id_list ;
            } elseif ($page_type === 2){
                $sql_contents = "DELETE FROM page_b_contents WHERE page_id = ". $id_list;
            }
            if ($forwhat === 'delete'){
                $sql_page = "DELETE FROM page_info WHERE page_id = ". $id_list;
            }
        } elseif ($designation === 'note_chapter'){
            if ($page_type === 1){
                $sql_contents = "DELETE FROM page_a_contents USING page_info 
                                 WHERE page_a_contents.page_id = page_info.page_id 
                                 AND page_info.chapter_id IN (". $id_list .")";
            }elseif($page_type === 2){
                $sql_contents = "DELETE FROM page_b_contents USING page_info 
                                 WHERE page_b_contents.page_id = page_info.page_id 
                                 AND page_info.chapter_id IN (". $id_list .")";
            }
            $sql_page = "DELETE FROM page_info WHERE chapter_id IN (". $id_list .")";
        }

        //先にコンテンツから削除
        $stmt_contents = $this->dbh->prepare($sql_contents);
        $bool_contents = $stmt_contents->execute();

        if ($forwhat === 'delete') {
            $stmt_page = $this->dbh->prepare($sql_page);
            $bool_page = $stmt_page->execute();
            $bool = ($bool_contents === true) && ($bool_page === true) ? true : false ;
        }else{
            $bool = ($bool_contents === true) ? true : false ;
        }

        return $bool;
    }


    //typeBで登録してい画像ファイルを削除
    public function removeFiles(string $designation, $id_list):bool{
        if($designation === 'note_chapter'){
            $sql = "SELECT * FROM page_b_contents 
                    INNER JOIN page_info ON page_b_contents.page_id = page_info.page_id 
                    WHERE page_info.chapter_id IN (" . $id_list .")";
        }elseif($designation === 'page'){
            $sql = "SELECT * FROM page_b_contents WHERE page_id = ". $id_list ;
        }
        
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
        $get_info = $stmt->FetchAll(PDO::FETCH_ASSOC);


        $unlink_bool = array();
        foreach($get_info as $info){
            if($info['file_type'] === 'img'){
                $unlink_bool[] = unlink($info['data']);
            }
        }

        $bool = empty($unlink_bool) || !in_array(0, $unlink_bool) ? true : false ;
        return $bool;
    }
}

?>