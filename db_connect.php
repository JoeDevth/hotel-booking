<?php
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "hotel management";

$conn = mysqli_connect($servername, $username, $password, $databasename );
$conn -> set_charset("utf8");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
