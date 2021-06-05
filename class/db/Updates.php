<?php
class Updates extends Connect
{
    //dsn
    public function __construct()
    {
        parent::__construct();
    }

    public function updateNote($note_id, $color, $note_title):bool{
        $sql  = "UPDATE note_info SET note_title = :note_title, color = :color WHERE note_id = :note_id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':note_id', $note_id, PDO::PARAM_STR);
        $stmt->bindValue(':color', $color, PDO::PARAM_STR);
        $stmt->bindValue(':note_title', $note_title, PDO::PARAM_STR);
        $bool = $stmt->execute();

        return $bool;
    }
}
?>