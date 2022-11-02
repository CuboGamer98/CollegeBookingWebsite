<?php
function random_string($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}

if (isset($_FILES['file']['name']) && isset($_POST["name"]) && $_POST["name"] !== "") {
    $filename = $_FILES['file']['name'];

    $filename = random_string(50).".".pathinfo($filename, PATHINFO_EXTENSION);
    $location = "../uploaded_images/" . $filename;

    $response = 0;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
        $response = $location;

        require_once "dbh.inc.php";
        $sql = "INSERT INTO book_types (name, img_name) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($conn);
    
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("admin_panel.php?error=erroraccepting");
            exit();
        }
    
        mysqli_stmt_bind_param($stmt, "ss", $_POST["name"], $location);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    
        header("admin_panel.php?error=addedclass");
        exit();
    }

    echo $response;
    exit;
}
echo 0;
