<?php
$db_host = "localhost:3307";
$db_user = "root";
$db_pass = "";
$db_name = "sinnam_db";

// Create connection
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (isset ($_SESSION['Username'])) {
    session_destroy();
}
?>
