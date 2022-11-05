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

<ul id="topbar">
    <li class="li-button" <?php if ($filename === "index") { echo "data-current-page"; } ?>>
        <div class="button">
            <a href=<?php if ($filename === "admin_panel") { $t = "./"; } else { $t = ""; } echo $t."index.php" ?>>Inico</a>
        </div>
    </li>
    <li class="li-button" <?php if ($filename === "incidence") { echo "data-current-page"; } ?>>
        <div class="button">
            <a href=<?php if ($filename === "admin_panel") { $t = "./"; } else { $t = ""; } echo $t."incidence.php" ?>>Incidencias</a>
        </div>
    </li>
    <li class="li-button user-account">
        <div class="button">
            <img src="images/user.svg">
            <p id="username"><?php echo $_SESSION["username"]; ?></p>
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
                    echo '<a href="' . $url . '">' . $text . '</a>';
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