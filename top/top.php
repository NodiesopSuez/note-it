<?php
session_start();
session_regenerate_id();

//外部ファイル読込
require_once('../class/config/Config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../head.php')?>
    <link rel="stylesheet" type="text/css" href="../main/template.css">
    <link rel="stylesheet" type="text/css" href="./top.css">
</head>
<body>
    <div class="container">
        <div class="catch_logo">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 66.9 66.62"><?=Config::LOGO_MARK?></svg>
        </div>
    </div>


    <script src="./top.js" type="text/javascript"></script>
</body>
</html>