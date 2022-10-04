<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'set':
            set($_POST['name'], $_POST['value']);
            break;
        case 'get':
            get($_POST['name'], $_POST['def']);
            break;
    }
}
function get($name, $default) {
    if (isset($_COOKIE[$name])) {
        echo $_COOKIE[$name];
        exit();
    }
    echo $default;
    exit();
}

function set($name, $value) {
    setcookie($name, $value, time()+60*60*24*30);
    exit();
}