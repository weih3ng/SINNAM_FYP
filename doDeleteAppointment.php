<?php
session_start();

include 'dbfunctions.php';

// Check if the user is logged in (joc)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    // Prepare delete query
    $query = "DELETE FROM appointments WHERE appointment_id = $appointment_id";
    $result = mysqli_query($link, $query);

    if ($result) {
        // Appointment successfully deleted
        $_SESSION['delete_msg'] = "Appointment successfully deleted";
    } else {
        // Failed to delete appointment
        $_SESSION['delete_msg'] = "Failed to delete appointment";
    }

    // Redirect back to viewAppointment.php
    header("Location: viewAppointment.php");
    exit();
} else {
    // If 'id' is not provided in POST, redirect to viewAppointment.php
    header("Location: viewAppointment.php");
    exit();
}
?>
