<?php
class Connect {
    protected $dbh;

    public function __construct(){
        $db = parse_url(getenv("DATABASE_URL"));

        $this -> dbh = new PDO("pgsql:" . sprintf(
            "host=%s;port=%s;user=%s;password=%s;dbname=%s",
            $db["host"],
            $db["port"],
            $db["user"],
            $db["pass"],
            ltrim($db["path"], "/")
        ));

        $this -> dbh -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
}
?>
