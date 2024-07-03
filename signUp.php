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
    <title>Registration Page</title>
    <style>
        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            height: calc(150vh - 120px);
        }

        .left-decoration img {
            max-width: 730px;
            margin-left: -120px;
            margin-top: 35px;
        }

        .register-form-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px 40px;
            width: 400px;
            margin-left: 50px;
        }

        .register-form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: black;
        }

        .register-form-container p {
            text-align: center;
        }

        .register-form-container label {
            display: block;
            margin-bottom: 5px;
        }

        .register-form-container input[type="text"],
        .register-form-container input[type="email"],
        .register-form-container input[type="password"],
        .register-form-container input[type="date"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #DC3545;
            border-radius: 20px;
            background-color: #F8D7DA;

        }

        .register-form-container input[type="radio"] {
            margin-left: 1px;
        }

        .register-form-container .radio-label {
            margin-right: 15px;
            
        }

        .register-form-container .gender-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            justify-content: space-between;
        }

        .gender-radio-buttons {
            display: flex;
            justify-content: flex-end;
            flex-grow: 1;
        }

        .register-form-container .btn {
            display: block;
            width: 100%;
            width: 150px;
            padding: 10px;
            background-color: #80352F;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            margin: 0 auto;
        }

        .register-form-container .btn:hover {
            background-color: #6b2c27;
        }

        .register-form-container .terms {
            font-size: 15px;
            text-align: center;
            margin-top: 10px;
        }
        .ipsFieldRow_required {
    font-size: 10px;
    
    text-transform: uppercase;
    color: #aa1414;
    font-weight: 500

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

        <!-- Register Container -->
        <div class="container">
        <div class="left-decoration">
            <img src="images/2.png" alt="Decoration">
        </div>
        <div class="register-form-container">
            <h1>Create New Account</h1>
            <p>Already a member? <a href="login.php">Log in</a></p>
            <form action="doSignUp.php" method="POST">
                <label for="idName">
                    <i class="fa-solid fa-user" style="color: #949494;"></i> Name<span class="ipsFieldRow_required" style="margin-left: 270px;">Required</span>
                </label>
                <input id="idName" type="text" name="name" required>
                <label for="idAge">
                    <i class="fa-solid fa-calendar" style="color: #949494;"></i> Age<span class="ipsFieldRow_required" style="margin-left: 285px;">Required</span>
                </label>
                <input id="idAge" type="text" name="age" required>
                <label for="idEmail">
                    <i class="fa-solid fa-envelope" style="color: #949494;"></i> Email<span class="ipsFieldRow_required" style="margin-left: 270px;">Required</span>
                </label>
                <input id="idEmail" type="email" name="email" required>
                <label for="idPassword">
                    <i class="fa-solid fa-lock" style="color: #949494;"></i> Password<span class="ipsFieldRow_required" style="margin-left: 240px;">Required</span>
                </label>
                <input id="idPassword" type="password" name="password" required>
                <label for="idDob">
                    <i class="fa-solid fa-calendar-alt" style="color: #949494;"></i> Date of Birth<span class="ipsFieldRow_required" style="margin-left: 220px;">Required</span>
                </label>
                <input id="idDob" type="date" name="dob" required>
                <div class="gender-container">
                    <label class="gender-label">
                        <i class="fa-solid fa-venus-mars" style="color: #949494;"></i> Gender<span class="ipsFieldRow_required" style="margin-left: 10px;">Required</span>
                    </label>
                    <div class="gender-radio-buttons">
                    <label class="radio-label">
                        <input type="radio" name="gender" value="male" required> Male
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="female" required> Female
                    </label>
                </div>
            </div>
                <button type="submit" class="btn">Sign up</button>
                <p class="terms">
                    By clicking "SIGN UP", I acknowledge that I have read, understood and agree that I am bound by the <a href="#">Account Terms of Use</a>
                </p>
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
                <span style="margin-right: 10px;">Follow us</span> <!-- Added a span to apply margin -->
                <a href="https://www.facebook.com/profile.php?id=167794019905102&_rdr"><i class="fa-brands fa-facebook"></i></a>
            </div>
        </footer>
</body>
</html>
