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
            flex-grow: 1;  /* This helps to distribute space more evenly */
            justify-content: center; /* Center all navbar links */
            gap: 20px; /* Adjust the gap to manage space between navigation links */
            margin-right: 480px; 
        }

        .nav-custom {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 10px; /* Small padding around text and icon */
            margin-left: 10px; /* Manage left margin to bring items closer */
            margin-right: 10px; /* Manage right margin to bring items closer */
        }

        .nav-custom:last-child {
            margin-right: 0; 
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            height: calc(100vh - 120px);
        }

        .left-decoration img {
            max-width: 730px;
            margin-left: -120px;
            margin-top: 35px;
        }

        .login-form {
            background: white;
            padding: 25px;
            margin: 100px;
            height: 400px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .login-form h1 {
            color: #80352F;
            text-align: center;
        }

        .login-form p {
            text-align: center;
        }

        .login-form form {
            display: flex;
            flex-direction: column;
        }

        #idEmail {
            border-radius: 20px;
            width: 330px; 
            height: 35px; 
            border: 1px solid #DC3545; 
            background-color: #F8D7DA;
            margin-left: 110px;
        }
            
        #idPassword {
            border-radius: 20px;
            width: 330px; 
            height: 35px; 
            border: 1px solid #DC3545; 
            background-color: #F8D7DA;
            margin-left: 110px;
        }

        #idLoginBtn {
            background-color: #80352F;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 50px;
            border: none;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            width: 150px;
            margin-left: 200px;
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
        <a class="nav-custom"></a>
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
            <h1 >Welcome Back</h1>
            <form>
                <label for="idEmail" style="display: block; margin-bottom: 5px; margin-left: 120px;">
                    <i class="fa-solid fa-envelope" style="color: #949494;"></i> Email
                </label>
                <input id="idEmail" type="text" name="email" required/>
                <br/><br/>
                <label for="idPassword" style="display: block; margin-bottom: 5px; margin-left: 120px;">
                    <i class="fa-solid fa-lock" style="color: #949494;"></i> Password
                </label>
                <input id="idPassword" type="password" required/>
                <br/><br/>

                <button id="idLoginBtn" type="submit">Login</button>
                
                <p>By clicking "LOGIN", I acknowledge that I have read, understood and agree that I am bound by the <a href="#">Account Terms of Use</a>.</p>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <img src="images/logo.jpeg" alt="logo" class="logo">
        <div class="footer-links">
            <a href="home.html">Home</a>
            <a href="#">About Us</a>
            <a href="#">Services</a>
            <a href="#">Contact Us</a>
        </div>
        <div class="social-media">
            Follow us <br>
            <a href="https://www.facebook.com/profile.php?id=167794019905102&_rdr"><i class="fa-brands fa-facebook" style="color: #ffffff;"></i></a>
        </div>
    </footer>
</body>
</html>
