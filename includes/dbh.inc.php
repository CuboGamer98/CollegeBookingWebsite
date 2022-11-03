<?php

$serverName = "localhost";
$dBUserName = "root";
$dBPassword = "";
$dBName = "account";

$conn = mysqli_connect($serverName, $dBUserName, $dBPassword, $dBName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");