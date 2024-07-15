<?php
session_start();

include 'dbfunctions.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}


// Get the user patient ID from the session
$patients_id = $_SESSION['patients_id'];

// Delete any testimonials associated with the patient before deleting the patient
$deleteTestimonials = "DELETE FROM testimonials WHERE patients_id = $patients_id";
if (mysqli_query($link, $deleteTestimonials)) {

    // If testimonials were successfully deleted or none existed, delete the patient
    $deletePatient = "DELETE FROM patients WHERE patients_id = $patients_id";
    
    if (mysqli_query($link, $deletePatient)) {
        session_destroy();
        header('Location: signUp.php'); 
        exit;
    } else {
        // Error deleting the patient
        echo "Error deleting patient: " . mysqli_error($link);
    }
} else {
    // Error deleting testimonials
    echo "Error deleting testimonials: " . mysqli_error($link);
}

// Close connection
mysqli_close($link);

?>
