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
    <title>Delete Appointment Page</title>
    <style>
        .delete-container {
            display: flex; /*lays out the flex items in a column, vertically from top to bottom */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            padding: 50px 20px;
            height: calc(100vh - 100px); /* Adjust height to fit within the viewport */
        }

        .delete-box {
            background-color: #DECFBC;
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .delete-box h1 {
            margin-bottom: 40px;
            text-align:center;
        }

        .form-group {
            display: flex; /* Makes the container a flex container so that items are well-aligned */
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            margin-bottom: 10px;
            font-weight: bold;
            width: 80px; 
            text-align: right;
            padding-right: 30px; /* Added padding for spacing */

        }

        .form-group input[type="text"], 
        .form-group input[type="date"], 
        .form-group input[type="time"] {
            display: block; /*starts on a new line and takes up the full width available */
            width: 250px; /* Adjust the width to be longer */
            padding: 10px;
            border-radius: 30px;
            border: 1px solid #ccc;
            flex: 1; /* take up available space within the .form-group container*/
        }

        .delete-box .btn {
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

        .delete-box .btn:hover {
            background-color: #6b2c27;
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

    <!-- Delete Appointment Container -->
    <div class="delete-container">
        <div class="delete-box">
            <h1>Delete Appointment</h1>
            <form method="post" action="viewAppointment.php">
            <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name">
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date">
                </div>
                <div class="form-group">
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time">
                </div>
                <br>
                <button type="submit" class="btn">Delete</button>
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
