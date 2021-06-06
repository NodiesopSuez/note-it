<?php
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

}
?>