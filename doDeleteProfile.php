<?php
session_start();

include 'dbfunctions.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Get the user's patient ID from the session
$patients_id = $_SESSION['patients_id'];

// Prepare query to delete user from patients table
$query = "DELETE FROM patients WHERE patients_id = $patients_id";

if (mysqli_query($link, $query)) {
    // If user was successfully deleted
    // Log the user out
    session_destroy();

    // Redirect to homepage or login page
    header('Location: signUp.php'); 
    exit;
} else {
    // Error deleting the user
    echo "Error: " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
?>
