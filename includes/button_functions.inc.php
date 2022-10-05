<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'accept':
            accept($_POST['email']);
            break;
        case 'delete':
            delete($_POST['email']);
            break;
        case 'admin':
            admin($_POST['email'], $_POST['value']);
            break;
        case 'options':
            options($_POST['option'], $_POST['value']);
            break;
    }
}

function accept($email) {
    require_once "dbh.inc.php";
    require_once "functions.inc.php";
    acceptUser($conn, $email);
}

function delete($email) {
    require_once "dbh.inc.php";
    require_once "functions.inc.php";
    deleteUser($conn, $email);
}

function admin($email, $value) {
    require_once "dbh.inc.php";
    require_once "functions.inc.php";
    setAdmin($conn, $email, $value);
}

function options($option, $value) {
    require_once "dbh.inc.php";
    require_once "functions.inc.php";
    changeOption($conn, $option, $value);
}