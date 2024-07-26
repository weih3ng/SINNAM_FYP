<?php

include 'dbfunctions.php';

// Checking & Retrieving Username
if (isset($_POST['username'])) {
    $username = $_POST['username'];

    $query = "SELECT * FROM patients WHERE username = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

// If greater than 0, means that the username already exists. If not it echoes "taken".
    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "taken";
    } else {
        echo "available";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
    }
    
?>
