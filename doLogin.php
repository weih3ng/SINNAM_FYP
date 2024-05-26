<?php
session_start();

$db_host = "localhost:3307";
$db_user = "root";
$db_pass = "";
$db_name = "sinnam_db";

// Create connection
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

$email = $_POST['email'];
$password = $_POST['password'];

$msg = "";

// Query to fetch user credentials
$query = "SELECT * FROM patients 
    WHERE email = '$email' 
    AND Password = '$password'";

$result = mysqli_query($link, $query);

mysqli_close($link);
?>


<?php if ($result && mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_array($result);
    $_SESSION['patient_id'] = $row['PatientID'];
    $_SESSION['username'] = $row['Username'];
    $_SESSION['name'] = $row['Name'];
    $_SESSION['birthdate'] = $row['DOB'];

    $msg = "Login Successful";

} else {
    $_SESSION['error_message'] = "Invalid email or password. Please try again.";
    header('Location: login.php');
    $msg = "Login Failed";

}
?>





