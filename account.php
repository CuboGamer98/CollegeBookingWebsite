<?php
session_start();
if (isset($_SESSION["username"]) === false || isset($_SESSION["useremail"]) === false) {
    header("location: includes/logout.inc.php");
    exit;
}

$filename = basename(debug_backtrace()[0]["file"], ".php");
if ($filename === "admin_panel") {
    require_once "includes/dbh.inc.php";
    require_once "includes/functions.inc.php";

    if (getIsAdmin($conn, $_SESSION["useremail"]) === false) {
        header("location: index.php");
        exit();
    }
}
?>

<ul>
    <li class="user-account">
        <div class="dropdown-button">
            <img src="images/user.png">
            <p><?php echo $_SESSION["username"]; ?></p>
        </div>
        <div class="dropdown-content">
            <?php
            if (isset($_SESSION["useremail"]) === true) {
                require_once "includes/dbh.inc.php";
                require_once "includes/functions.inc.php";

                if (getIsAdmin($conn, $_SESSION["useremail"]) === true) {
                    $text = "Panel de administrador";
                    $url = "admin_panel.php";
                    if ($filename == "admin_panel") {
                        $text = "Página principal";
                        $url = "index.php";
                    }
                    echo '<a href="'.$url.'">'.$text.'</a>';
                    unset($text);
                    unset($url);
                    unset($filename);
                }
            }
            ?>
            <a href="includes/logout.inc.php">Cerrar sesión</a>
        </div>
    </li>
</ul>