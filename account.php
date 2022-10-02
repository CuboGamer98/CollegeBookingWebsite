<?php
session_start();
if (isset($_SESSION["username"]) === null || isset($_SESSION["useremail"]) === null) {
    header("location: includes/logout.inc.php");
    exit();
}
?>

<ul>
    <li class="user-account">
        <div class="dropdown-button">
            <img src="images/user.png">
            <p>Cuenta</p>
        </div>
        <div class="dropdown-content">
            <?php
            if (isset($_SESSION["useremail"]) === true) {
                require_once "includes/dbh.inc.php";
                require_once "includes/functions.inc.php";

                if (getIsAdmin($conn, $_SESSION["useremail"]) === true) {
                    echo '<a href="">Panel de administrador</a>';
                }
            }
            ?>
            <a href="includes/logout.inc.php">Cerrar sesión</a>
        </div>
    </li>
</ul>