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

// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $dob = mysqli_real_escape_string($link, $_POST['dob']);
    $gender = mysqli_real_escape_string($link, $_POST['gender']);
    $contactnumber = mysqli_real_escape_string($link, $_POST['contactnumber']);
    $password = mysqli_real_escape_string($link, $_POST['password']);

    // Prepare the update query
    $query = "UPDATE patients SET 
                name = '$name', 
                username = '$username',
                email = '$email', 
                dob = '$dob', 
                gender = '$gender', 
                contactnumber = '$contactnumber',
                password = '$password'
              WHERE patients_id = $patients_id";

    // Execute the query
    if (mysqli_query($link, $query)) {
        // Redirect to profile page with success message
        $_SESSION['success_message'] = "Profile updated successfully.";
        header('Location: userProfile.php');
    } else {
        // Display error message
        echo "Error: " . mysqli_error($link);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Redirect to profile page if accessed directly
    header('Location: userProfile.php');
}
?>
