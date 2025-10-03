<?php
$hostName = "localhost";
$dbUser   = "root";
$dbPass   = "";
$dbName   = "shopzone";  // your database name

$conn = mysqli_connect($hostName, $dbUser, $dbPass, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
