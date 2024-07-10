<?php
session_start(); // Start the session

include 'dbfunctions.php';

// Fetch testimonials from the database
$sql = "SELECT * FROM testimonials";
$result = mysqli_query($link, $sql);

// Check if there are any testimonials
if (mysqli_num_rows($result) > 0) {
    $testimonials = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
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
            object-fit: cover; /* Image covers the area without distortion */
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
            object-fit: cover; /* Image covers the area without distortion */
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

        .testimonial-comments {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px; 
        }

        .testimonial-patient_username {
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
        <?php if (isset($_SESSION['username'])): ?>
            <p style='margin-top: 17px;'>Welcome, <b><a href="userProfile.php" style="text-decoration: underline;"><?php echo htmlspecialchars($_SESSION['username']); ?></a></b>!</p>

            <a class="nav-custom" href="logout.php">
                <i class="fa-solid fa-right-to-bracket"></i> Logout
            </a>  
        <?php else: ?>
            <a class="nav-custom" href="signUp.php">
                <i class="fa-solid fa-user"></i> Sign Up
            </a>
            <a class="nav-custom" href="login.php">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </a>  
        <?php endif; ?>
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
                                <!-- Dynamically generate indicators based on testimonials count -->
                                <?php foreach ($testimonials as $key => $testimonial): ?>
                                    <li data-target="#testimonialCarousel" data-slide-to="<?php echo $key; ?>" <?php echo $key === 0 ? 'class="active"' : ''; ?>></li>
                                <?php endforeach; ?>
                            </ul>
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                <!-- Dynamically generate carousel items based on testimonials -->
                                <?php foreach ($testimonials as $key => $testimonial): ?>
                                    <div class="carousel-item <?php echo $key === 0 ? 'active' : ''; ?>">
                                        <h5 class="testimonial-patient_username"><?php echo htmlspecialchars($testimonial['patient_username']); ?></h5>
                                        <p class="testimonial-comments"><?php echo htmlspecialchars($testimonial['comments']); ?></p>
                                    </div>
                                <?php endforeach; ?>
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
