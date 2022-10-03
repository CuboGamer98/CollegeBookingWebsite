<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'accept':
            accept($_POST['value']);
            break;
        case 'delete':
            delete($_POST['value']);
            break;
        case 'admin':
            admin($_POST['value']);
            break;
    }
}

function accept($email) {
    error_log(print_r("0", TRUE)); 
    require_once "includes/dbh.inc.php";
    require_once "includes/functions.inc.php";
    acceptUser($conn, $email);
    //exit;
}

function delete($email) {
    require_once "includes/dbh.inc.php";
    require_once "includes/functions.inc.php";
    deleteUser($conn, $email);
    exit;
}


function admin($value) {
    exit;
}
