<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'accept':
            accept();
            break;
        case 'delete':
            delete();
            break;
        case 'admin':
            admin($_POST['value']);
            break;
    }
}

function accept() {

    exit;
}

function delete() {

    exit;
}


function admin($value) {
    echo $value;
    //exit;
}
