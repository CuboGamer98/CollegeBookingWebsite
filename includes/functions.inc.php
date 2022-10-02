<?php

function emptyInputSignup($name, $email, $pwd, $pwdrepeat) {
    return (empty($name) || empty($email) || empty($pwd) || empty($pwdrepeat));
}

function invalidName($name) {
    return (!preg_match('/^[a-zA-Z\s]+$/', $name));
}

function invalidEmail($email) {
    return (!filter_var($email, FILTER_VALIDATE_EMAIL) || substr(strrchr($email, "@"), 1) != "alexia.cnsfatima.es");
}

function pwdMatch($pwd, $pwdrepeat) {
    return ($pwd == $pwdrepeat);
}

function emailExists($conn, $name, $email, $db = "users") {
    $sql = "SELECT * FROM ".$db." WHERE usersName = ? OR usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $name, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    
    return false;

    mysqli_stmt_close($stmt);
}

function emailExistsPending($conn, $anme, $email) {
    return emailExists($conn, $anme, $email, "pending_users");
}

function createPendingUser($conn, $name, $email, $pwd) {
    $sql = "INSERT INTO pending_users (usersName, usersEmail, Userspwd) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../signup.php?error=none");
    exit();
}

function emptyInputLogin($name_email, $pwd) {
    return (empty($name_email) || empty($pwd));
}

function loginUser($conn, $name_email, $pwd) {
    $emailExists = emailExists($conn, $name_email, $name_email);

    if ($emailExists === false) {
        $emailExistsP = emailExistsPending($conn, $name_email, $name_email);
        if ($emailExistsP === false) {
            header("location: ../login.php?error=wronglogin");
        } else {
            header("location: ../login.php?error=emailpending");
        }
        exit();
    }

    $pwdHashed = $emailExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed);

    echo 1;
    if ($checkPwd === true) {
        session_start();
        $_SESSION["userid"] = $emailExists["usersId"];
        $_SESSION["username"] = $emailExists["usersName"];
        $_SESSION["useremail"] = $emailExists["usersEmail"];
        echo 1;
        header("location: ../index.php");
        exit();
        echo 10;
    }
}

function getIsAdmin($conn, $email) {
    $emailExists = emailExists($conn, "", $email);

    if ($emailExists === false) {
        return false;
    }

    return $emailExists["isAdmin"] === 1;
}