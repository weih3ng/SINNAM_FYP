<?php
session_start();

include 'dbfunctions.php';

if (isset($_POST['id'])) {
    $appointment_id = $_POST['id'];

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
