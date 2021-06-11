<?php
class Users extends Connect {
    //dsn
    public function __construct(){
        parent::__construct();
    }

    //ユーザーデータを検索($category== 'email'or'user_id')
    public function findUserInfo (string $data,string $category):array {
        $sql = "SELECT * FROM user_info WHERE " .$category. "=:data";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':data', $data, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        //falseが返された時はからの配列を返却
        if(empty($data)){
            return [];
        }
        return $data;
    }

    //ユーザー情報登録
    public function registerUserData(
    string $nick_name, string $gender, string $email, string $birth, string $pass, string $registration_dt):bool{
        $sql = "INSERT INTO user_info(nick_name,gender,email,birth,pass,registration_dt) 
                VALUES(:nick_name,:gender,:email,:birth,:pass,:registration_dt)";
        $stmt = $this->dbh->prepare($sql);
        
        $stmt->bindValue(':nick_name',$nick_name,PDO::PARAM_STR);
        $stmt->bindValue(':gender',$gender,PDO::PARAM_STR);
        $stmt->bindValue(':email',$email,PDO::PARAM_STR);
        $stmt->bindValue(':birth',$birth,PDO::PARAM_STR);
        $stmt->bindValue(':pass',$pass,PDO::PARAM_STR);
        $stmt->bindValue(':registration_dt',$registration_dt,PDO::PARAM_STR);
        
        $bool = $stmt->execute();
        
        return $bool;
    }

    //ユーザー情報更新
    public function updateUserData(
        int $user_id, string $nick_name, string $gender, string $email,string $birth, string $pass):bool{
        $sql = "UPDATE user_info
                SET nick_name = :nick_name,
                    gender = :gender,
                    email = :email,
                    birth = :birth,
                    pass = :pass
                WHERE user_id = :user_id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindValue(':user_id',$user_id,PDO::PARAM_INT);
        $stmt->bindValue(':nick_name', $nick_name, PDO::PARAM_STR);
        $stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':birth', $birth, PDO::PARAM_STR);
        $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);

        $bool = $stmt->execute();

        return $bool;
    }
}
?>
