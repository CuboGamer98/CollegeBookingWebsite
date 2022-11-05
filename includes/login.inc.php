<?php

if (isset($_POST["submit"])) {
    ob_start();
    session_start();
    
    $_SESSION['name_email'] = $_POST['name_email'];
    $url= $_SERVER['HTTP_REFERER'];

    $name_email = $_POST["name_email"];
    $pwd = $_POST["pwd"];

    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if (emptyInputLogin($name_email, $pwd) !== false) {
        header("location: ./login.php?error=emptyinput");
        exit();
    }

    loginUser($conn, $name_email, $pwd);
} else {
    header("location: ./login.php");
}