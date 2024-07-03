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
    <!-- jQuery and Bootstrap JS -->
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


        /* Styles for Carousel */
        .carousel-item img {
            width: 100%; 
            max-height: 500px; 
            object-fit: cover; /* Image covers the area without distorted */
        }



        /* Styles for How To Book */
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
            font-weight: bold;
        }

        .line {
            flex-grow: 1; /* Allows the lines to take up available space */
            border-top: 1.5px solid #000000; 
            max-width: 300px; 
        }

        .booking-info h2 {
            font-size: 24px; 
            color: #333; 
            text-align: center; 
        }

        .booking-info {
            margin-top: 50px; 
            margin-bottom: 20px; 
            padding-left: 15%;
        }

        .booking-info ol {
            padding-left: 20px; 
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
            text-decoration: none; 
        }

        .book-now-btn:hover {
            background-color: #6b2c27; 
            color: white;
        }

        .booking-image img {
            width: 100%; 
            height: auto; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
            border-radius: 50px; 
        }



        /* Styles for Testimonials */
        .testimonial-section {
            padding-top: 10px;  
            padding-bottom: 80px;
        }

        .testimonial-section img {
            position: relative; /* Allows for positioning adjustments */
            left: 8%;
            width: 650px;  
            height: 350px; 
            object-fit: cover; /* Image covers the area without distorted */
        }

        .testimonial-content {
            position: absolute;
            top: 50%;  /* Positions the top edge of the box */
            right: 10%; /* Positions the right edge of the box */
            background: white;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            width: 35%; 
            transform: translateY(-50%); /* Center the box */ 
            margin-bottom: 30px; 
        }

        .testimonial-text {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px; 
        }

        .testimonial-author {
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }

        #carouselIndicators2 {
            position: absolute;
            bottom: -40px; /* Position the indicators below the content */
            left: 20%; /* Move the indicators to the left */
            width: 100%;
            justify-content: center; 
            padding-left: 0;
            margin-right: 15%;
            list-style: none;
        }

        #carouselIndicators2 li {
            background-color: gray; 
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 2px;
        }

        #carouselIndicators2 .active {
            background-color: #80352F; 
        }



        /* Additional specificity to ensure it overrides default styles */
        .navbar-links a, .nav-custom {
            color: inherit; /* Ensure the text color have the same as the parent elements  */
            text-decoration: none; 
        }
        
        .navbar-links a:hover, .nav-custom:hover {
            color: inherit; /* Ensures the links do not change color on hover */
            text-decoration: none; 
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
            <a href="viewAppointment.php">View Appointment</a>
            <a href="contact.php">Contact Us</a>
        </div>

        <!-- Sign Up & Login Button -->


        <?php
if (isset($_SESSION['username'])) { 
    // Display 'Welcome, username'
    echo "<p style='margin-top: 17px;'>Welcome, <b>" . htmlspecialchars($_SESSION['username']) . "</b>!</p>";
    ?>
    <a class="nav-custom" href="logout.php">
        <i class="fa-solid fa-right-to-bracket"></i> Logout
    </a>  
<?php } else { ?>
    <a class="nav-custom" href="signUp.php">
        <i class="fa-solid fa-user"></i> Sign Up
    </a>
    <a class="nav-custom" href="login.php">
        <i class="fa-solid fa-right-to-bracket"></i> Login
    </a>  
<?php } ?>



    </div>



    <!-- Home Container -->
    <div class="home-container">

        <!-- Carousel Section -->
        <div class="carousel-section">
            <div id="demo" class="carousel slide" data-ride="carousel">

                <!-- Indicators -->
                <ul class="carousel-indicators" id="carouselIndicators1">
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
                    <a href="appointment.php" class="book-now-btn btn">Book Now</a>
                </div>
                <div class="col-md-6 booking-image">
                    <img src="images/4.jpg" alt="Image describing booking process" class="img-fluid">
                </div>
            </div>
        </div>

        <!-- Testimonial Section -->
        <div class="testimonial-section container mt-5">
            <div class="title-wrapper text-center">
                <hr class="line">
                <h2>Testimonials</h2>
                <hr class="line">
            </div>
            <div class="row">
                <div class="col-lg-12 position-relative">
                    <img src="images/5.jpg" alt="Testimonial Background" class="img-fluid">
                    <div class="testimonial-content">
                        <div id="testimonialCarousel" class="carousel slide" data-ride="carousel">

                            <!-- Indicators -->
                            <ul class="carousel-indicators" id="carouselIndicators2">
                                <li data-target="#testimonialCarousel" data-slide-to="0" class="active"></li>
                                <li data-target="#testimonialCarousel" data-slide-to="1"></li>
                                <li data-target="#testimonialCarousel" data-slide-to="2"></li>
                                <li data-target="#testimonialCarousel" data-slide-to="3"></li>
                                <li data-target="#testimonialCarousel" data-slide-to="4"></li>
                            </ul>

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <h5 class="testimonial-author">Jin H</h5>
                                    <p class="testimonial-text">Doctor is very patient and powerful, the most powerful Chinese medicine 
                                        practitioner I have ever encountered, and the results are very good. It's just a long wait in line, 
                                        but it's well worth it.</p>
                                </div>
                                <!-- More testimonials can be added here -->
                                <div class="carousel-item">
                                    <h5 class="testimonial-author">Tan Wei Ling</h5>
                                    <p class="testimonial-text">I was thoroughly impressed by the professionalism and warmth of the staff. 
                                        The treatment I received was both effective and nurturing. Highly recommended for anyone looking for quality care!</p>
                                </div>
                                <div class="carousel-item">
                                    <h5 class="testimonial-author">Liu Xing</h5>
                                    <p class="testimonial-text">I have been a patient of Sin Nam Medical Hall for many years. The doctors are 
                                        knowledgeable and caring. I have always been treated with respect and kindness. I highly recommend this clinic.</p>
                                </div>
                                <div class="carousel-item">
                                    <h5 class="testimonial-author">Sophie Lee</h5>
                                    <p class="testimonial-text">I have been a patient of Sin Nam Medical Hall for many years. The doctors are 
                                        knowledgeable and caring. I have always been treated with respect and kindness. I highly recommend this clinic.</p>
                                </div>
                                <div class="carousel-item">
                                    <h5 class="testimonial-author">Ahmed J.</h5>
                                    <p class="testimonial-text">I have been a patient of Sin Nam Medical Hall for many years. The doctors are 
                                        knowledgeable and caring. I have always been treated with respect and kindness. I highly recommend this clinic.</p>
                                </div>
                            </div>
                        </div>
                    </div>
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