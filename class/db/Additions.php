<?php 
class Addition extends Connect {
    //dsn文
    public function __construct(){
        parent::__construct();
    }
   
    //新規ノート追加
    public function createNewNote(
        string $note_title, int $user_id, string $color){
            $sql = "INSERT INTO note_info (note_title, user_id, color) VALUES (:note_title, :user_id, :color) RETURNING note_id";

            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':note_title', $note_title);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->bindValue(':color', $color);
            $stmt->execute();

            $new_note_id = $stmt->fetch(PDO::FETCH_ASSOC);
            $note_id = $new_note_id['note_id'];

            return $note_id;
    }

    //新規チャプター追加
    public function createNewChapter(
        string $chapter_title, int $page_type, int $note_id){
            $sql = "INSERT INTO chapter_info (chapter_title, page_type, note_id) VALUES (:chapter_title, :page_type, :note_id) RETURNING chapter_id";
            
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':chapter_title', $chapter_title);
            $stmt->bindValue(':page_type', $page_type);
            $stmt->bindValue(':note_id', $note_id);
            $stmt->execute();

            $new_chapter_id = $stmt->fetch(PDO::FETCH_ASSOC);
            $chapter_id = $new_chapter_id['chapter_id'];

            return $chapter_id;
    }

    //新規ページ追加
    public function createNewPage(
        string $page_title, string $addition_dt, int $chapter_id){
            $sql = "INSERT INTO page_info (page_title, addition_dt, chapter_id) VALUES (:page_title, :addition_dt, :chapter_id) RETURNING page_id";

            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':page_title', $page_title);
            $stmt->bindValue(':addition_dt', $addition_dt);
            $stmt->bindValue(':chapter_id', $chapter_id);
            $stmt->execute();

            $new_page_id = $stmt->fetch(PDO::FETCH_ASSOC);
            $page_id = $new_page_id['page_id'];

            return $page_id;
    }

    // type A // コンテンツ登録
    public function registerContentsA(
        string $meaning, string $syntax, string $syn_memo, string $example, string $ex_memo, string $memo, int $page_id) :bool{
            $sql = "INSERT INTO page_a_contents (meaning, syntax, syn_memo, example, ex_memo, memo, page_id) VALUES (:meaning, :syntax, :syn_memo, :example, :ex_memo, :memo, :page_id)";
        
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':meaning', $meaning);
        $stmt->bindValue(':syntax', $syntax);
        $stmt->bindValue(':syn_memo', $syn_memo);
        $stmt->bindValue(':example', $example);
        $stmt->bindValue(':ex_memo', $ex_memo);
        $stmt->bindValue(':memo', $memo);
        $stmt->bindValue(':page_id', $page_id);
        $bool = $stmt->execute();

        return $bool;
    }

    // type B // コンテンツ登録
    public function registerContentsB(
        int $page_id, array $add_contents) :bool{
            foreach($add_contents as $contents_block){
                $sql = "INSERT INTO page_b_contents (data, file_type, page_id) VALUES (:data, :file_type, :page_id)";

                $stmt = $this->dbh->prepare($sql);
                $stmt->bindValue(':data', $contents_block['data'], PDO::PARAM_STR);
                $stmt->bindValue(':file_type', $contents_block['file_type'], PDO::PARAM_STR);
                $stmt->bindValue(':page_id', $page_id);
                $bool = $stmt->execute();
            }
        return $bool;
    }


}



?>