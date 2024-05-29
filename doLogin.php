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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">  <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->  
    <title>Login Successful Page</title>

    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
        }

        .navbar, footer {
            background-color: #80352F;
            color: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            width: 90px;
            margin-left: 10px;
        }

        .navbar-links a, .footer-links a {
            color: white;
            text-decoration: none;
            margin: 0 25px;
        }

        .navbar-links {
            display: flex;
            flex-grow: 1;
            justify-content: center;
            gap: 20px;
            margin-right: 480px;
        }

        .nav-custom {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 10px;
            margin-left: 10px;
            margin-right: 10px;
        }

        .nav-custom:last-child {
            margin-right: 0;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            min-height: calc(100vh - 120px);
            padding: 20px;
            box-sizing: border-box;
        }

        .content-box {
            background-color: #DECFBC;
            border-radius: 80px;
            padding: 150px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .content-box h1 {
            color: black;
            margin-bottom: 30px;
            font-size: 40px;
        }

        .content-box p {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .content-box button {
            background-color: #80352F;
            color: white;
            border: none;
            border-radius: 30px;
            padding: 10px 35px;
            cursor: pointer;
        }

        .social-media {
            margin-top: 2px;
        }

        .social-media a {
            color: white;
            text-decoration: none;
            font-size: 24px;
        }

    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <a class="navbar-brand text-dark" href="home.html">
            <img src="images/logo.jpeg" alt="logo" class="logo">
        </a>
        <div class="navbar-links">
            <a href="home.html">Home</a>
            <a href="#">About Us</a>
            <a href="#">Appointment</a>
            <a href="#">Contact Us</a>
        </div>

        <!-- Sign Up & Login Button -->
        <a class="nav-custom" href="#">
            <i class="fa-solid fa-user" style="color: #ffffff;"></i> Sign Up
        </a>
        <a class="nav-custom" href="login.php" style="color: #F8D7DA;">
            <i class="fa-solid fa-right-to-bracket" style="color: #F8D7DA;"></i> Login
        </a>  
    </div>

    <!-- Login Container -->
    <div class="container">
        <div class="content-box">
            <h1>Login Successful!</h1><br>
            <p>You have successfully logged in.</p><br>
            <a href="home.html">
                <button><b>Home</b></button>
            </a>
        </div>
    </div>

        <!-- Footer -->
        <footer>
        <img src="images/logo.jpeg" alt="logo" class="logo">
        <div>
            @ 2024 Sin Nam Medical Hall All Rights Reserved
        </div>
        <div class="social-media">
            <span style="margin-right: 10px;">Follow us</span>
            <a href="https://www.facebook.com/profile.php?id=167794019905102&_rdr"><i class="fa-brands fa-facebook" style="color: #ffffff;"></i></a>
        </div>
    </footer>
    </body>
</html>
