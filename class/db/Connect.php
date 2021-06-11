<?php
class Connect {
    const DB_NAME = 'note_it';
    const DB_HOST = 'localhost';
    const DB_USER = 'yamiyo_tomoshibi';
    const DB_PASS = 'n0.d1esop_suez';
    protected $dbh;

    public function __construct(){
        $dsn = 'pgsql:dbname='.self::DB_NAME.';host='.self::DB_HOST.';port=5432';
        $this->dbh = new PDO($dsn,self::DB_USER,self::DB_PASS);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
}
?>
