<?php
function random_string($length) {
    $key = '';
    $keys = array_merge(range(0, 9), range('a', 'z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }

    return $key;
}

function is_image($path){
	$a = getimagesize($path);
	$image_type = $a[2];
	
    if (in_array($image_type , array(IMAGETYPE_BMP, IMAGETYPE_GIF, IMAGETYPE_IFF, IMAGETYPE_JB2, IMAGETYPE_JP2, IMAGETYPE_JPC, IMAGETYPE_JPX, IMAGETYPE_PNG, IMAGETYPE_PSD, IMAGETYPE_SWC, IMAGETYPE_SWF, IMAGETYPE_XBM, IMAGETYPE_AVIF, IMAGETYPE_JPEG, IMAGETYPE_WBMP, IMAGETYPE_WEBP, IMAGETYPE_COUNT, IMAGETYPE_TIFF_II, IMAGETYPE_TIFF_MM, IMAGETYPE_JPEG2000))) {
        return true;
    }
    return false;
}

if (isset($_FILES['file']['name']) && isset($_POST["name"]) && $_POST["name"] !== "") {
    $filename = $_FILES['file']['name'];

    if (is_image($_FILES['file']['tmp_name']) === false) {
        echo 0;
        exit;
    }

    $filename = random_string(50).".".$ext;
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
    
        require("./functions.inc.php");
        $name = sanitize_xss($_POST["name"]);

        mysqli_stmt_bind_param($stmt, "ss", $name, $location);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    
        header("admin_panel.php?error=addedclass");
        exit();
    }

    echo $response;
    exit;
}
echo 0;
