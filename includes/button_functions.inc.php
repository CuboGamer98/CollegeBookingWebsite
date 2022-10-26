<?php
if (isset($_POST['action'])) {
    require_once "dbh.inc.php";
    require_once "functions.inc.php";
    switch ($_POST['action']) {
        case 'accept':
            acceptUser($conn, $_POST['email']);
            break;
        case 'delete':
            deleteUser($conn, $_POST['email']);
            break;
        case 'admin':
            setAdmin($conn, $_POST['email'], $_POST['value']);
            break;
        case 'options':
            changeOption($conn, $_POST['option'], $_POST['value']);
            break;
        case 'addautobook':
            addAutoBook($conn, $_POST['email'], $_POST['weekday'], $_POST['start'], $_POST['end'], $_POST['class'], $_POST['grade'], $_POST['book']);
            break;
        case 'deleteautobook':
            removeAutoBook($conn, $_POST['email'], $_POST['weekday'], $_POST['start'], $_POST['end'], $_POST['class'], $_POST['grade'], $_POST['book']);
            break;
        case 'makeautobook':
            makeBookInMass($conn, $_POST['month'], $_POST['year']);
            break;
        case 'removebooking':
            removeBooking($conn, $_POST['id'], $_POST['start'], $_POST['end'], $_POST['class'], $_POST['grade'], $_POST['book'], $_POST['name'], $_POST['date']);
            break;
        case 'removeallbookingsfromyear':
            removeBookingsFromYear($conn, $_POST['year']);
            break;
        case 'changeincidentemail':
            changeIncidentEmail($conn, $_POST['newemail']);
            break;
        case 'sendincidence':
            sendIncidentEmail($conn, $_POST['text']);
            break;
    }
}
