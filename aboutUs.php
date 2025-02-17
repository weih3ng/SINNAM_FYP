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
    <link rel="stylesheet" href="style.css">
    <title>About Us</title>
    

    <style>

        .container {
            padding: 20px;
            text-align: center;
            background-color: #F1EDE2;
        }

        .section {
            margin: 40px 0;
        }



        .section p {
            font-size: 18px;
            margin: 10px 0;
        }

        .who-we-are {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            text-align: left;
        }

        .who-we-are p {
            font-size: 28px; 
            line-height: 1.5; 
        }

        .who-we-are img {
            max-width: 100%;
            height: auto;
            max-width: 650px; 
        }

        .our-doctor {
            margin-right: 10px;
            margin-top: -100px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: -100px;
        }
        .our-doctor p {
            font-size: 28px; 
            line-height: 1.5; 
        }

        .our-doctor img {
            max-width: 100%;
            height: auto;
            max-width: 800px; 
        }

        .our-doctor div {
            text-align: left;
        }

        .our-doctor h3 {
            margin: 10px 0;
        }

        .why-choose-us {
            display: flex;
            justify-content: center;
            gap: 80px;
            font-size: 30px; 
        }

        .why-choose-us div {
            text-align: center;
            width: 25%; 
        }

        .why-choose-us img {
            margin-top: 60px;
            width: 200px; 
            height: 200px; 
            margin-bottom: 15px; 
        }

        .why-choose-us p {
            font-size: 30px;
            margin: 0; 
            margin-bottom: 45px;
        }
        p {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }


        /* Navigation Bar Styling (joc) */ 
        .navbar-links a.current {
            position: relative;
            color: white; 
        }
        
        .navbar-links a.current:after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -10px; 
            height: 3px; 
            background-color: white; 
            border-radius: 2px; 
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
            <a href="home.php">Home<span class="underline"></span></a>
            <a href="aboutUs.php" class="current">About Us</a>
            <a href="appointment.php">Appointment<span class="underline"></span></a>
            <?php if (isset($_SESSION['username'])): ?>
            <a href="viewAppointment.php">View Appointment<span class="underline"></span></a>
            <?php else: ?>
                <?php endif; ?>
            <a href="contact.php">Contact Us<span class="underline"></span></a>
        </div>

        <!-- Sign Up & Login Button -->
        <?php if (isset($_SESSION['username'])): ?>
    <?php if ($_SESSION['username'] === 'doctor' || $_SESSION['username'] === 'admin'): ?>
        <p style='margin-top: 17px;'>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <?php else: ?>
        <p style='margin-top: 17px;'>Welcome, <a href='userProfile.php' style='text-decoration: underline; color: white;'><?php echo htmlspecialchars($_SESSION['username']); ?></a>!</p>
    <?php endif; ?>
<?php endif; ?>

            <?php if (isset($_SESSION['username'])): ?>
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

    <!-- Main Content -->
    <div class="container">
    <div class="out-doctor-text">
        <p style="margin: 40px 0; text-align: center; font-size: 45px; color: #80352F;"><b>Who Are We?</b></p>
    </div>
    
        <!-- Who Are We Section -->
        <div class="section who-we-are">
            
            <div>
                <p>Sin Nam Medical Hall Pte Ltd is a family-run<br> business at #01-101 Yishun Street 71, Block<br> 729, Singapore. With one experienced TCM<br> doctor and a skilled admin, we offer<br> personalized, holistic healthcare solutions.<br> We blend traditional wisdom with modern<br> practices to ensure our community's well-<br>being.</p>
            </div>
            <div>
                <img src="images/about1.png" alt="Decorative Image"> 
            </div>
        </div>

        <!-- Our Doctor Section -->
        <div class="who-we-are-text">
        <p style="margin: 40px 0; text-align: center; font-size: 45px; color: #80352F;"><b>Our Doctor</b></p>
    </div>
        <div class="section our-doctor">
            <img src="images/doc2.png" alt="Doctor Image"> 
            <div>
                <h1>Desmond Sin</h1>
                <p>Desmond Sin is a distinguished<br> Traditional Chinese Medicine (TCM)<br> practitioner known for his expertise<br> in herbal medicine and TCM diagnostics. He is<br> dedicated to helping patients<br> achieve balance and well-being through<br> personalized treatment plans that integrate<br> traditional wisdom with modern insights.</p>
            </div>
        </div>
        <div class="why-choose-us-text">
        <p style="margin: 40px 0; text-align: center; font-size: 45px; color: #80352F;"><b>Why Choose Us?</b></p>
    </div>

        <!-- Why Choose Us Section -->
        <div class="section why-choose-us">
            <div>
                <img src="images/icon1.png" alt="Experienced Practitioner">
                <p><b>Experienced <br>Practitioner</b></p>
            </div>
            <div>
                <img src="images/icon2.png" alt="Family-Run Business">
                <p><b>Family-Run <br>Business</b></p>
            </div>
            <div>
                <img src="images/icon3.png" alt="Personalized Care">
                <p><b>Personalized <br>Care</b></p>
            </div>
            <div>
                <img src="images/icon4.png" alt="Trusted Reputation">
                <p><b>Trusted <br>Reputation</b></p>
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
