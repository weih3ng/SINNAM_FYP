<?php
session_start(); // Starting the session 

$db_host = "localhost:3307";
$db_user = "root";
$db_pass = "";
$db_name = "sinnam_db";

// Creating the connection
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Retrieving the email and password from POST request
$email = $_POST['email'];
$password = $_POST['password'];

$msg = "";

// Fetching user details and executing it
$query = "SELECT * FROM patients WHERE email = '$email' AND Password = '$password'";
$result = mysqli_query($link, $query);

mysqli_close($link); // Closing db connection
?>

<?php 
// If results exist, fetch into $row
if ($result && mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_array($result);
    $_SESSION['patient_id'] = $row['PatientID'];
    $_SESSION['username'] = $row['Username'];
    $_SESSION['name'] = $row['Name'];
    $_SESSION['birthdate'] = $row['DOB'];

    $msg = "Login Successful";
} 

// If no results found, set error msg and redirect to login.php
else {
    $_SESSION['error_message'] = "Invalid email or password. Please try again.";
    header('Location: login.php');
    $msg = "Login Failed";

}
?>





