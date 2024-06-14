<?php 
session_start(); // Start the session

include 'dbfunctions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Slideshow--> 
    <!-- Load jQuery and Bootstrap JS at the end of the body for better loading performance -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">  <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome --> 
    <link rel="stylesheet" href="style.css"> <!-- External stylesheet for navigation bar and footer -->

    <title>Home Page</title>

    <style>

        .home-container {
            background-color: #F1EDE2;
        }

        .carousel-item img {
            width: 100%; /* Ensures the image takes the full width of the container */
            max-height: 500px; /* Adjust this value based on your preference */
            object-fit: cover; /* Ensures the image covers the area without distorting aspect ratio */
        }

        .how-to-book-section {
            padding: 40px 0; /* Top and bottom padding */
        }

        .title-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
        }

        .title-wrapper h2 {
            margin: 0 -30px; /* Adds spacing between the lines and the text */
        }

        .line {
            flex-grow: 1; /* Allows the lines to take up available space */
            border-top: 1.5px solid #000000; /* Style the line */
            max-width: 300px; /* Sets a maximum width for the lines */
        }

        .booking-info h2 {
            font-size: 24px; /* Larger font size for heading */
            color: #333; /* Dark color for text */
            text-align: center; /* Center align the title */
        }

        .booking-info {
            margin-top: 50px; /* Additional spacing */
            margin-bottom: 20px; /* Additional spacing */
        }

        .booking-info ol {
            padding-left: 20px; /* Proper indentation for list */
        }

        .book-now-btn {
            background-color: #80352F;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 30px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.1s ease;
        }

        .book-now-btn:hover {
            background-color: #6b2c27; /* Darker shade on hover */
        }

        .booking-image img {
            width: 100%; /* Full width within the column */
            height: auto; /* Maintain aspect ratio */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Subtle shadow for depth */
            border-radius: 50px; /* Rounded corners for the image */
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
            <a href="#">Appointment</a>
            <a href="contact.php">Contact Us</a>
        </div>

        <!-- Sign Up & Login Button -->
        <a class="nav-custom" href="signUp.php">
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


    <!-- Home Container -->
    <div class="home-container">

        <!-- SlideShow Section -->
        <div class="slideshow-section">
            <div id="demo" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ul class="carousel-indicators">
                    <li data-target="#demo" data-slide-to="0" class="active"></li>
                    <li data-target="#demo" data-slide-to="1"></li>
                </ul>

                <!-- The slideshow -->
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="images/banner1.jpg" alt="Banner 1">
                    </div>
                    <div class="carousel-item">
                        <img src="images/banner2.jpg" alt="Banner 2">
                    </div>
                </div>

                <!-- Left and right controls -->
                <a class="carousel-control-prev" href="#demo" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#demo" data-slide="next">
                <span class="carousel-control-next-icon"></span>
                </a>
            </div>
        </div>

        <!-- How to Book Section -->
        <div class="how-to-book-section container">
            <div class="title-wrapper text-center">
                <hr class="line">
                <h2>How to Book?</h2>
                <hr class="line"> 
            </div>
            <div class="row">
                <div class="col-md-6 booking-info">
                    <p>Book Your Appointment in Three Simple Steps:</p>
                    <ol>
                        <li>Choose a date and time</li>
                        <li>Receive an appointment confirmation</li>
                        <li>Booking successful!</li>
                    </ol>
                    <button class="book-now-btn">Book Now</button>
                </div>
                <div class="col-md-6 booking-image">
                    <img src="images/4.jpg" alt="Image describing booking process" class="img-fluid">
                </div>
            </div>
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
            <span style="margin-right: 10px;">Follow us</span>
            <a href="https://www.facebook.com/profile.php?id=167794019905102&_rdr"><i class="fa-brands fa-facebook"></i></a>
        </div>
    </footer>

    </body>
</html>