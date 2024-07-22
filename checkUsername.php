<?php
include 'dbfunctions.php';

if (isset($_POST['username'])) {
    $username = $_POST['username'];

    $query = "SELECT * FROM patients WHERE username = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "taken";
    } else {
        echo "available";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>
