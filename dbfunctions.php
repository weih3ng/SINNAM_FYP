<?php
$db_host = "localhost:3306";
$db_user = "root";
$db_pass = "";
$db_name = "sinnam_db";

// Create connection
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
