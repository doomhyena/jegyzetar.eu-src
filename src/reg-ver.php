<?php
require "assets/php/db.php";

session_start();

if (!isset($_SESSION['ver_id'])) {
    header("Location: reglog.php");
    exit;
}

if (isset($_GET['token'])) {
    echo $_GET['token'];
    $lekerdezes = "SELECT * FROM tokens";
    $talalt_sorok = $conn->query($lekerdezes);
    while($sor = $talalt_sorok->fetch_assoc()){
        if($_SESSION["ver_id"] == $sor["user_id"] && $_GET['token'] == $sor["token"]){
            $conn->query("UPDATE users SET email_verified = 1");
            header("Location: reglog.php");
            
        }
    }

}
