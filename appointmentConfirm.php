<?php 
session_start(); // Start the session

include 'dbfunctions.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">  <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome --> 
    <link rel="stylesheet" href="style.css"> <!-- External stylesheet for navigation bar and footer -->
    <title>Appointment Confirmation Page</title>
    <style>
        .confirmation-container {
            display: flex; /* Makes the container a flex container so that items are well-aligned */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            padding: 50px 20px;
            height: calc(100vh - 100px); /* Adjust height to fit within the viewport */
        }

        .confirmation-box {
            background-color: #DECFBC;
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .confirmation-box img {
            width: 90px;
            height: 90px;
        }

        .confirmation-box h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .confirmation-box p {
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 60px; /* add more space between buttons */
            margin-bottom: 10px;
        }

        .confirmation-box .btn {
            display: inline-block; /*sit inline with any other inline or inline-block elements next to it */
            padding: 10px 50px;
            margin: 5px;
            background-color: #80352F;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .confirmation-box .btn:hover {
            background-color: #6b2c27;
        }

        .confirmation-box .link {
            display: inline-block;
            margin-top: 10px;
            color: #0000FF;
            text-decoration: underline;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <a class="navbar-brand text-dark" href="home.php">
            <img src="images/logo.jpeg" alt="logo" class="logo">
        </a>
        <div class="navbar-links">
            <a href="home.php">Home</a>
            <a href="aboutUs.php">About Us</a>
            <a href="appointment.php">Appointment</a>
            <a href="contact.php">Contact Us</a>
        </div>

        <!-- Sign Up & Login Button -->
        <a class="nav-custom" href="signUp.php">
            <i class="fa-solid fa-user"></i> Sign Up
        </a>

        <?php

        if (isset($_SESSION['username'])) { ?>


        <a class="nav-custom" href="logout.php">
            <i class="fa-solid fa-right-to-bracket"></i> Logout
        </a>  
        <?php }else { ?>
            <a class="nav-custom" href="login.php">
            <i class="fa-solid fa-right-to-bracket"></i> Login
        </a>  

        
        <?php } ?>

        </div>

    <!-- Confirmation Container -->
    <div class="confirmation-container">
        <div class="confirmation-box">
            <img src="images/check_mark.png" alt="check mark">
            <h1>Thank you for booking an appointment<br>with us!</h1>
            <p>Please check your email for more information.</p>
            <div class="button-container">
                <a href="editAppointment.php" class="btn">Edit</a>
                <a href="viewAppointment.php" class="btn">View</a>
            </div>
            <a href="appointment.php" class="link">Book another appointment</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
    <a href="home.php">
        <img src="images/logo.jpeg" alt="logo" class="logo">
    </a>
        <div>
            @ 2024 Sin Nam Medical Hall All Rights Reserved
        </div>
        <div class="social-media">
            <span style="margin-right: 10px;">Follow us</span> <!-- Added a span to apply margin -->
            <a href="https://www.facebook.com/profile.php?id=167794019905102&_rdr"><i class="fa-brands fa-facebook"></i></a>
        </div>
    </footer>
</body>
</html>
