<?php 
session_start(); // Start the session

$db_host = "localhost:3307";
$db_user = "root";
$db_pass = "";
$db_name = "sinnam_db";

// Create connection
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (isset ($_SESSION['Username'])) {
    session_destroy();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">  <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->  
    <title>Login Page</title>

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
            color: #949494;
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
        <div class="left-decoration">
            <img src="images/1.png" alt="Decoration">
        </div>
        
        <div class="login-form">
            <h1>Welcome Back</h1>

           


            <form method="post" action="doLogin.php">
                <label for="idEmail">
                    <i class="fa-solid fa-envelope"></i> Email
                </label>
                <input id="idEmail" type="text" name="email" required/>
                
                <label for="idPassword">
                    <i class="fa-solid fa-lock"></i> Password
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


