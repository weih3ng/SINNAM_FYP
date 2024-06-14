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
    <link rel="stylesheet" href="style.css">
    <title>Login Page</title>

    <style>
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            min-height: calc(100vh - 120px);
            padding: 20px;
            box-sizing: border-box;
        }

        .left-decoration img {
            max-width: 850px;
            margin-left: -120px;
            margin-top: 35px;
        }

        .login-form {
            background: white;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 550px;
            width: 100%;
            min-height: 400px; /* Ensure a longer height */
        }

        .login-form h1 {
            color: #80352F;
            text-align: center;
            margin-bottom: 20px;
        }

        .login-form p {
            text-align: center;
        }

        .login-form form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .login-form label {
            align-self: flex-start;
            margin-left: 15%;
            margin-bottom: 5px;
            color: #000000;
        }

        #idEmail, #idPassword {
            border-radius: 20px;
            width: 70%;
            height: 30px;
            border: 1px solid #DC3545;
            background-color: #F8D7DA;
            margin-bottom: 15px;
            padding: 5px 5px;
        }

        #idLoginBtn {
            background-color: #80352F;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 50px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.1s ease;
        }

        #idLoginBtn:hover {
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
    
    <!-- Login Container -->
    <div class="login-container">
        <div class="left-decoration">
            <img src="images/1.png" alt="Decoration">
        </div>
        
        <div class="login-form">
            <h1>Welcome Back</h1>
            <form method="post" action="doLogin.php">
                <label for="idEmail">
                    <i class="fa-solid fa-envelope" style="color: #949494;"></i> Email
                </label>
                <input id="idEmail" type="text" name="email" required/>
                
                <label for="idPassword">
                    <i class="fa-solid fa-lock"  style="color: #949494;"></i> Password
                </label>
                <input id="idPassword" type="password" name="password" required/>
                
                <button id="idLoginBtn" type="submit">Login</button>
                <?php if (isset($_SESSION['error_message'])): ?> <!--set error msg (displays in red)-->
                <p style="color: red;"><?php echo $_SESSION['error_message']; ?></p>
                <?php unset($_SESSION['error_message']); ?> <!-- unsets error msg to clear from session-->
            <?php endif; ?> <!-- End of 'if' block-->

                <p>By clicking "LOGIN", I acknowledge that I have read, understood and agree that I am bound by the <a href="#">Account Terms of Use</a>.</p>
            </form>
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


