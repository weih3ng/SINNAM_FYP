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

// Begin transaction
mysqli_begin_transaction($link);

try {
    // Delete appointments associated with the patient
    $deleteAppointments = "DELETE FROM appointments WHERE patients_id = $patients_id";
    if (!mysqli_query($link, $deleteAppointments)) {
        throw new Exception("Error deleting appointments: " . mysqli_error($link));
    }

    // Delete testimonials associated with the patient
    $deleteTestimonials = "DELETE FROM testimonials WHERE patients_id = $patients_id";
    if (!mysqli_query($link, $deleteTestimonials)) {
        throw new Exception("Error deleting testimonials: " . mysqli_error($link));
    }

    // Delete the patient
    $deletePatient = "DELETE FROM patients WHERE patients_id = $patients_id";
    if (!mysqli_query($link, $deletePatient)) {
        throw new Exception("Error deleting patient: " . mysqli_error($link));
    }

    // Commit transaction
    mysqli_commit($link);
    session_destroy();
    header('Location: signUp.php');
    exit;
} catch (Exception $e) {
    // Rollback transaction
    mysqli_rollback($link);
    echo $e->getMessage(); // Or handle error appropriately
}

// Close connection
mysqli_close($link);
?>
