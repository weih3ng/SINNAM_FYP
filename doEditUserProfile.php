<?php
session_start();
include 'dbfunctions.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$patients_id = $_SESSION['patients_id'];

// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $dob = mysqli_real_escape_string($link, $_POST['dob']);
    $gender = mysqli_real_escape_string($link, $_POST['gender']);
    $contactnumber = mysqli_real_escape_string($link, $_POST['contactnumber']);
    $password = mysqli_real_escape_string($link, $_POST['password']);

    // Check if the new username is already taken by another user
    $checkUsernameQuery = "SELECT * FROM patients WHERE username = '$username' AND patients_id != $patients_id";
    $checkUsernameResult = mysqli_query($link, $checkUsernameQuery);
    if (mysqli_num_rows($checkUsernameResult) > 0) {
        // Username already taken
        header('Location: userProfile.php?error=username_taken'); // Redirect with a query string
        exit;
    }

    // Prepare the update query
    $updateQuery = "UPDATE patients SET 
                name = '$name', 
                username = '$username',
                email = '$email', 
                dob = '$dob', 
                gender = '$gender', 
                contactnumber = '$contactnumber',
                password = '$password'
            WHERE patients_id = $patients_id";

    // Execute the query
    if (mysqli_query($link, $updateQuery)) {
        header('Location: userProfile.php?success=profile_updated'); // Redirect to profile page with success flag
    } else {
        echo "Error updating record: " . mysqli_error($link); // Display SQL error
    }

    mysqli_close($link);
} else {
    header('Location: userProfile.php'); // Redirect to profile page if the script is accessed directly
}
?>
