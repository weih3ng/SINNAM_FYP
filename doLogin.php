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

// If results exist, fetch into $row
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $msg = "Login Successful";
    $_SESSION['success_message'] = "Successfully logged in.";
} 
// If no results found, set error msg and redirect to login.php
else {
    $_SESSION['error_message'] = "Invalid email or password. Please try again.";
    header('Location: login.php');
    $msg = "Login Failed";
}

mysqli_close($link); 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Status</title>
    <style>
       
        .center {
            text-align: center;
            margin-top: 50px; 
        }
        .big-text {
            font-size: 36px; 
        }
    </style>
</head>
<body>
    <div class="center">
        <p class="big-text"><?php echo isset($_SESSION['success_message']) ? $_SESSION['success_message'] : ''; ?></p>
    </div>
</body>
</html>
