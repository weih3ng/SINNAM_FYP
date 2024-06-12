<?php
session_start(); // Starting the session 

include 'dbfunctions.php';

// Retrieving the email and password from POST request
$email = $_POST['email'];
$password = $_POST['password'];

$msg = "";

// Fetching user details and executing it
$query = "SELECT * FROM patients WHERE email = '$email' AND Password = SHA1('$password')";
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
    <link rel="stylesheet" href="style.css">
    <title>Login Successful Page</title>

    <style>


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



    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="navbar">
        <a class="navbar-brand text-dark" href="home.html">
            <img src="images/logo.jpeg" alt="logo" class="logo">
        </a>
        <div class="navbar-links">
            <a href="home.php">Home</a>
            <a href="#">About Us</a>
            <a href="#">Appointment</a>
            <a href="#">Contact Us</a>
        </div>

        <!-- Sign Up & Login Button -->
        <a class="nav-custom" href="#">
            <i class="fa-solid fa-user"></i> Sign Up
        </a>

        <?php

        if (isset($_SESSION['Username'])) { ?>

        <a class="nav-custom" href="login.php">
            <i class="fa-solid fa-right-to-bracket"></i> Login
        </a>  

        <?php }else { ?>
            
        <a class="nav-custom" href="logout.php">
            <i class="fa-solid fa-right-to-bracket"></i> Logout
        </a>  
        <?php } ?>

        </div>

    <!-- Login Container -->
    <div class="container">
        <div class="content-box">
            <h1>Login Successful!</h1><br>
            <p>You have successfully logged in.</p><br>
            <a href="home.php">
                <button>Home</button>
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
        <a href="https://www.facebook.com/profile.php?id=167794019905102&_rdr"><i class="fa-brands fa-facebook"></i></a>
    </div>
    </footer>
</body>
</html>
