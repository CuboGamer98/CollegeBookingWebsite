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
            case 'addautobook':
                addauto($_POST['email'], $_POST['weekday'], $_POST['start'], $_POST['end'], $_POST['class'], $_POST['grade'], $_POST['book']);
                break;
                case 'deleteautobook':
                    deleteauto($_POST['email'], $_POST['weekday'], $_POST['start'], $_POST['end'], $_POST['class'], $_POST['grade'], $_POST['book']);
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

function addauto($email, $weekday, $start, $end, $class, $grade, $book) {
    require_once "dbh.inc.php";
    require_once "functions.inc.php";
    addAutoBook($conn, $email, $weekday, $start, $end, $class, $grade, $book);
}

function deleteauto($email, $weekday, $start, $end, $class, $grade, $book) {
    require_once "dbh.inc.php";
    require_once "functions.inc.php";
    removeAutoBook($conn, $email, $weekday, $start, $end, $class, $grade, $book);
}