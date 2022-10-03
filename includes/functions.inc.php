<?php

use LDAP\Result;

function emptyInputSignup($name, $email, $pwd, $pwdrepeat) {
    return (empty($name) || empty($email) || empty($pwd) || empty($pwdrepeat));
}

function invalidName($name) {
    return (!preg_match('/^[A-zÀ-ú\s]+$/', $name));
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
    $sql = "INSERT INTO pending_users (usersName, usersEmail, usersPwd) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../signup.php?error=emailstartedpending");
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

    if ($checkPwd === true) {
        session_start();
        $_SESSION["userid"] = $emailExists["usersId"];
        $_SESSION["username"] = $emailExists["usersName"];
        $_SESSION["useremail"] = $emailExists["usersEmail"];
        header("location: ../index.php");
        exit();
    }
}

function getIsAdmin($conn, $email) {
    $emailExists = emailExists($conn, "", $email);

    if ($emailExists === false) {
        return false;
    }

    return $emailExists["isAdmin"] === 1;
}

function getDataFromTable($conn, $table) {
    $sql = "SELECT * FROM ".$table;
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result === false) {
        header("location: ../index.php?error=noresult");
        exit();
    }

    return $result;
}

function getPendingUsers($conn) {
    $result = getDataFromTable($conn, "pending_users");
    $return = array();
    while ($row = $result -> fetch_row()) {
        array_push($return, array($row[0], $row[1], $row[2]));
    }

    return $return;
}

function getUsers($conn) {
    $result = getDataFromTable($conn, "users");
    $return = array();
    while ($row = $result -> fetch_row()) {
        $state = false;
        if ($row[4] === 1) {
            $state = true;
        }
        array_push($return, array($row[0], $row[1], $row[2], $row[4]));
    }

    return $return;
}

function acceptUser($conn, $email) {
    $result = emailExistsPending($conn, "", $email);
    if ($result === false) {
        header("location: ../admin_panel.php?error=userdonesntexists");
        exit();
    }

    $sql = "INSERT INTO users (usersName, usersEmail, usersPwd) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../admin_panel.php?error=erroraccepting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $result["usersName"], $result["usersEmail"], $result["usersPwd"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = "DELETE FROM pending_users WHERE usersEmail=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../admin_panel.php?error=erroraccepting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../signup.php?error=useraccepted");
    exit();
}

function deleteUser($conn, $email) {
    $result = emailExists($conn, "", $email);
    if ($result === false) {
        header("location: ../admin_panel.php?error=userdonesntexists");
        exit();
    }

    $sql = "DELETE FROM users WHERE usersEmail=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../admin_panel.php?error=erroraccepting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../signup.php?error=userdeleted");
    exit();
}

function setAdmin($conn, $email, $value) {
    $result = emailExists($conn, "", $email);
    if ($result === false) {
        header("location: ../admin_panel.php?error=userdonesntexists");
        exit();
    }

    $sql = "UPDATE users SET isAdmin=? WHERE usersEmail=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../admin_panel.php?error=erroraccepting");
        exit();
    }
    
    $isAdminVar = (int)filter_var($value, FILTER_VALIDATE_BOOLEAN);
    mysqli_stmt_bind_param($stmt, "is", $isAdminVar, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../signup.php?error=adminadded");
    exit();
}

function addBooking($conn, $email, $id, $start, $end, $class, $grade, $book, $date) {
    $result = emailExists($conn, "", $email);
    if ($result === false) {
        header("location: ../calendario.php?error=userdonesntexists");
        exit();
    }

    $sql = "INSERT INTO bookings (id, start, end, name, class, grade, book, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../calendario.php?error=erroraccepting");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssssssss", $id, $start, $end, $result["usersName"], $class, $grade, $book, $date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../calendario.php?error=bookingcompleted");
    exit();
}

function getBookings($conn) {
    $myArray = array();
    $result = getDataFromTable($conn, "bookings");
    while($row = $result->fetch_assoc()) {
        $myArray[] = $row;
    }
    echo json_encode($myArray);
}