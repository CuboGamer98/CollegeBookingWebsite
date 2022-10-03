<?php

if (isset($_POST["submit"])) {
    ob_start();
    session_start();
    
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['email'] = $_POST['email'];
    $url= $_SERVER['HTTP_REFERER'];

    $name = $_POST["name"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdrepeat = $_POST["pwdrepeat"];

    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    if (emptyInputSignup($name, $email, $pwd, $pwdrepeat) !== false) {
        header("location: ../signup.php?error=emptyinput");
        exit();
    }

    if (invalidName($name) !== false) {
        header("location: ../signup.php?error=invalidname");
        exit();
    }

    if (invalidEmail($email) !== false) {
        header("location: ../signup.php?error=invalidemail");
        exit();
    }

    if (pwdMatch($pwd, $pwdrepeat) !== true) {
        header("location: ../signup.php?error=pwdnotmatch");
        exit();
    }

    if (emailExists($conn, $name, $email) !== false) {
        header("location: ../signup.php?error=emailinuse");
        exit();
    }

    if (emailExistsPending($conn, $name, $email) !== false) {
        header("location: ../signup.php?error=emailpending");
        exit();
    }

    createPendingUser($conn, $name, $email, $pwd);
} else {
    header("location: ../signup.php");
}
