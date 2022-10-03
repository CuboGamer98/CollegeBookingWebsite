<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'getbookings':
            getbooks();
            break;
        case 'book':
            book($_POST['id'], $_POST['start'], $_POST['end'], $_POST['class'], $_POST['grade'], $_POST['book'], $_POST['date']);
            break;
    }
}

function getbooks() {
    require_once "dbh.inc.php";
    require_once "functions.inc.php";
    getBookings($conn);
}

function book($id, $start, $end, $class, $grade, $book, $date) {
    session_start();
    if (isset($_SESSION["useremail"]) === null) {
        header("location: logout.inc.php");
        exit();
    }
    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    addBooking($conn, $_SESSION["useremail"], $id, $start, $end, $class, $grade, $book, $date);
}
