<?php
 require_once(dirname(__FILE__, 2).'/class/db/Additions.php');


class Updates extends Connect
{
    //dsn
    public function __construct()
    {
        parent::__construct();
    }

    //ノート情報を更新
    public function updateNote($note_id, $color, $note_title):bool{
        $sql  = "UPDATE note_info SET note_title = :note_title, color = :color WHERE note_id = :note_id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':note_id', $note_id, PDO::PARAM_STR);
        $stmt->bindValue(':color', $color, PDO::PARAM_STR);
        $stmt->bindValue(':note_title', $note_title, PDO::PARAM_STR);
        $bool = $stmt->execute();

        return $bool;
    }

    //チャプター情報を更新
    public function updateChapter($chapter_id, $chapter_title):bool{
        $sql = "UPDATE chapter_info SET chapter_title = :chapter_title WHERE chapter_id = :chapter_id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':chapter_id', $chapter_id, PDO::PARAM_STR);
        $stmt->bindValue(':chapter_title', $chapter_title, PDO::PARAM_STR);
        $bool = $stmt->execute();

        return $bool;
    }

    // ページ情報を更新 // typeA
    public function updatePageContentsA(array $page_contents):bool {
        $page_sql = "UPDATE page_info SET page_title = :page_title WHERE page_id = :page_id";
        $stmt = $this-> dbh->prepare($page_sql);
        $stmt->bindValue(':page_id', $page_contents['page_id'], PDO::PARAM_STR);
        $stmt->bindValue(':page_title', $page_contents['page_title'], PDO::PARAM_STR);
        $update_page = $stmt->execute();
        
        $contents_sql = "UPDATE page_a_contents 
                SET meaning  = :meaning,
                    syntax   = :syntax, 
                    syn_memo = :syn_memo,
                    example  = :example, 
                    ex_memo  = :ex_memo, 
                    memo     = :memo
                WHERE page_id = :page_id";
        $stmt = $this-> dbh->prepare($contents_sql);
        $stmt->bindValue(':page_id', $page_contents['page_id'], PDO::PARAM_INT);
        $stmt->bindValue(':meaning', $page_contents['meaning'], PDO::PARAM_STR);
        $stmt->bindValue(':syntax', $page_contents['syntax'], PDO::PARAM_STR);
        $stmt->bindValue(':syn_memo', $page_contents['syn_memo'], PDO::PARAM_STR);
        $stmt->bindValue(':example', $page_contents['example'], PDO::PARAM_STR);
        $stmt->bindValue(':ex_memo', $page_contents['ex_memo'], PDO::PARAM_STR);
        $stmt->bindValue(':memo', $page_contents['memo'], PDO::PARAM_STR);
        $update_contents = $stmt->execute();
        
        $bool = ($update_page === true && $update_contents === true ) ? true : false;
        
        return $bool;
    }
    
    // ページ情報を更新 // typeB
    public function updatePageContentsB(array $page_contents):bool{
        $update_title = "UPDATE page_info SET page_title = :page_title WHERE page_id = :page_id";
        $stmt = $this->dbh->prepare($update_title);
        $stmt->bindValue(':page_title', $page_contents['page_title'], PDO::PARAM_STR);
        $stmt->bindValue(':page_id', $page_contents['page_id'], PDO::PARAM_INT);
        $execute_bool[] = $stmt->execute();

        $delete_contents = "DELETE FROM page_b_contents WHERE page_id = :page_id";
        $stmt = $this->dbh->prepare($delete_contents);
        $stmt->bindValue(':page_id', $page_contents['page_id'], PDO::PARAM_STR);
        $execute_bool[] = $stmt->execute();

        $add_contents = new Addition;
        //$add_contents->registerContentsB()



        
        $bool = ($update_title === true && $delete_contents === true ) ? true : false;

        return $bool;
    }
}
?>