<?php
session_start(); // Start the session

include 'dbfunctions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contactnumber = $_POST['contactnumber'];
    $dob = $_POST['dob'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];

    // Check if the username already exists
    $query = "SELECT * FROM patients WHERE username = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Username already exists
        $_SESSION['error'] = "Username already exists. Please choose a different username.";
        header("Location: signUp.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $query = "INSERT INTO patients (name, username, email, contactnumber, dob, password, gender) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "sssssss", $name, $username, $email, $contactnumber, $dob, $hashed_password, $gender);

    if (mysqli_stmt_execute($stmt)) {
        // Registration successful
        $_SESSION['success'] = "Registration successful! You can now log in.";
        header("Location: login.php");
    } else {
        // Error inserting user
        $_SESSION['error'] = "An error occurred during registration. Please try again.";
        header("Location: signUp.php");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
} else {
    // If the request method is not POST, redirect to the sign-up page
    header("Location: signUp.php");
    exit();
}
?>
