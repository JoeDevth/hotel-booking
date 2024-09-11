<?php
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "hotel management";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $databasename );
$conn -> set_charset("utf8");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>