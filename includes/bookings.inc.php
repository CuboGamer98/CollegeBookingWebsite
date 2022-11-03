<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'getbookings':
            getbooks();
            break;
        case 'book':
            book($_POST['id'], $_POST['start'], $_POST['end'], $_POST['class'], $_POST['grade'], $_POST['book'], $_POST['date']);
            break;
        case 'getbookingsnow':
            require_once "dbh.inc.php";
            require_once "functions.inc.php";
            getBookingsNow($conn, $_POST["booktype"]);
            break;
    }
}

function getbooks() {
    require_once "dbh.inc.php";
    require_once "functions.inc.php";
    getBookings($conn, false);
}

function book($id, $start, $end, $class, $grade, $book, $date) {
    require_once "dbh.inc.php";
    require_once "functions.inc.php";

    session_start();
    if (isset($_SESSION["useremail"]) === null) {
        echo "includes/logout.inc.php";
        exit();
    }
    
    $class = sanitize_xss($class);
    $grade = sanitize_xss($grade);

    addBooking($conn, $_SESSION["useremail"], $id, $start, $end, $class, $grade, $book, $date);
}
