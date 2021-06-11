<?php
class Searches extends Connect {
    //dsn
    public function __construct(){
        parent::__construct();
    }

    //既存ノート情報検索($column:カラム,$id:ID) 'user_id','note_id'
    public function findNoteInfo(string $column, int $id):array{
        $sql = "SELECT * FROM note_info WHERE ". $column ."= :id ORDER BY note_id ASC";
        $stmt = $this -> dbh -> prepare($sql);
        $stmt -> bindValue(':id', $id, PDO::PARAM_INT);
        $stmt -> execute();
        $fetch_data = $stmt -> fetchall(PDO::FETCH_ASSOC);

        //falseならば空を返す
        $fetch_data ? : $note_list = [];

        //取得した情報をオブジェクトに格納
        foreach($fetch_data as $data){
            $note_list[$data['note_id']] = [
                'note_title' => $data['note_title'],
                'color'      => $data['color'],
            ];
        }
        return $note_list;
    }

    //該当id以外のノートリストを取得
    public function findOtherNoteInfo(int $user_id, string $column, int $note_id):array{
        $sql = "SELECT * FROM note_info WHERE user_id = :user_id AND NOT ". $column ." = :note_id ";
        $stmt = $this -> dbh -> prepare($sql);
        $stmt -> bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt -> bindValue(':note_id', $note_id, PDO::PARAM_INT);
        $stmt -> execute();
        $fetch_data = $stmt -> fetchall(PDO::FETCH_ASSOC);

        //falseならば空を返す
        $fetch_data ? : $note_list = [];

        //取得した情報をオブジェクトに格納
        foreach($fetch_data as $data){
            $note_list[$data['note_id']] = [
                'note_title' => $data['note_title'],
                'color'      => $data['color'],
            ];
        }
        return $note_list;
    }

    //chapter情報を検索($column:カラム,$id:ID) 'note_id','chapter_id'
    public function findChapterInfo(string $column, $id):array{
        $sql = "SELECT * FROM chapter_info WHERE ". $column ." = :id";
        $stmt = $this -> dbh -> prepare($sql);
        $stmt -> bindValue(':id', $id);
        $stmt -> execute();
        $fetch_data = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        //falseならば空の配列を返して修了
        $fetch_data ? : $chapter_list = [];

        //取得した情報をオブジェクトに格納
        foreach($fetch_data as $data) {
            $chapter_list[$data['chapter_id']] = [
                'chapter_title' => $data['chapter_title'],
                'page_type'     => $data['page_type'],
                'note_id'       => $data['note_id']
            ];
        }
        return $chapter_list;
    }

    //該当id以外のチャプターリストを取得
    public function findOtherChapterInfo(int $note_id, int $chapter_id):array{
        $sql = "SELECT * FROM chapter_info WHERE note_id = :note_id AND NOT chapter_id = :chapter_id ";
        $stmt = $this -> dbh -> prepare($sql);
        $stmt -> bindValue(':note_id', $note_id, PDO::PARAM_INT);
        $stmt -> bindValue(':chapter_id', $chapter_id, PDO::PARAM_INT);
        $stmt -> execute();
        $fetch_data = $stmt -> fetchall(PDO::FETCH_ASSOC);

        //falseならば空を返す
        $fetch_data ? : $chapter_list = [];

        //取得した情報をオブジェクトに格納
        foreach($fetch_data as $data){
            $chapter_list[$data['chapter_id']] = [
                'chapter_title' => $data['chapter_title']
            ];
        }
        return $chapter_list;
    }

    //chapter_idからpage情報を検索
    public function findPageInfo($chapter_id):array{
        if(!is_array($chapter_id)){}
        $sql = "SELECT * FROM page_info WHERE chapter_id = :chapter_id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':chapter_id', $chapter_id, PDO::PARAM_INT);
        $stmt->execute();
        $fetch_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //falseが返ってきたら空の配列を返す
        $fetch_data ? : $page_list = [];

        //取得した情報をオブジェクトに格納
        foreach($fetch_data as $data){
            $page_list[$data['page_id']] = [
                'page_title' => $data['page_title'],
            ];
        }
        return $page_list;
    }

    public function findPageContentsA($page_id){
        //ページ情報とコンテンツ内容をまとめて取得
        $sql = "SELECT * FROM page_a_contents  INNER JOIN page_info USING(page_id) WHERE page_a_contents.page_id = :page_id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':page_id', $page_id, PDO::PARAM_STR);
        $stmt->execute();
        $page_contents = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]; 

        return $page_contents;
    }

    public function findPageContentsB($page_id){
        //ページ情報取得
        $pageinfo_sql = "SELECT * FROM page_info WHERE page_id = :page_id";
        $get_pageinfo = $this->dbh->prepare($pageinfo_sql);
        $get_pageinfo->bindValue(':page_id', $page_id, PDO::PARAM_STR);
        $get_pageinfo->execute();
        $page_contents['page'] = $get_pageinfo->fetchAll(PDO::FETCH_ASSOC)[0];


        //コンテンツ情報だけ取得
        $contents_sql = "SELECT * FROM page_b_contents WHERE page_id = :page_id ORDER BY contents_id ASC";
        $get_contents = $this->dbh->prepare($contents_sql);
        $get_contents->bindValue(':page_id', $page_id, PDO::PARAM_STR);
        $get_contents->execute();
        $page_contents['contents'] = $get_contents->fetchAll(PDO::FETCH_ASSOC);

        return $page_contents;
    }

}

?>
