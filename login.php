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
        /* Existing styles */
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

        .ipsFieldRow_required {
            font-size: 10px;
            text-transform: uppercase;
            color: #aa1414;
            font-weight: 500
        }

        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.4); 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 600px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
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
            <a href="home.php">Home<span class="underline"></span></a>
            <a href="aboutUs.php">About Us<span class="underline"></span></a>
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
    
    <!-- Login Container -->
    <div class="login-container">
        <div class="left-decoration">
            <img src="images/1.png" alt="Decoration">
        </div>
        
        <div class="login-form">
            <h1>Welcome Back</h1>
            <form method="post" action="doLogin.php">
                <label for="idEmail">
                    <i class="fa-solid fa-envelope" style="color: #949494;"></i> Email<span class="ipsFieldRow_required" style="margin-left: 260px;">Required</span>
                </label>
                <input id="idEmail" type="text" name="email" required/>
                
                <label for="idPassword">
                    <i class="fa-solid fa-lock"  style="color: #949494;"></i> Password<span class="ipsFieldRow_required" style="margin-left: 230px;">Required</span>
                </label>
                <input id="idPassword" type="password" name="password" required/>
                
                <button id="idLoginBtn" type="submit">Login</button>
                <?php if (isset($_SESSION['error_message'])): ?> <!--set error msg (displays in red)-->
                <p style="color: red;"><?php echo $_SESSION['error_message']; ?></p>
                <?php unset($_SESSION['error_message']); ?> <!-- unsets error msg to clear from session-->
            <?php endif; ?> <!-- End of 'if' block-->

                <p>By clicking "LOGIN", I acknowledge that I have read, understood and agree that I am bound by the <a href="#" id="termsLink">Account Terms of Use</a>.</p>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div id="termsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Account Terms of Use</h2>
        <p><strong>1. Introduction</strong><br>
        Welcome to Sin Nam Medical Hall’s website. By accessing or using our website, you agree to comply with and be bound by the following terms and conditions. Please read these terms carefully. If you do not agree with any part of these terms, you should not use our website.</p>
        
        <p><strong>2. Definitions</strong><br>
        "Website" refers to Sin Nam Medical Hall's website.<br>
        "We", "Us", "Our" refers to Sin Nam Medical Hall.<br>
        "User", "You" refers to any individual or entity using our website.</p>
        
        <p><strong>3. Use of the Website</strong><br>
        You agree to use the website only for lawful purposes and in a manner that does not infringe the rights of, restrict, or inhibit anyone else's use and enjoyment of the website. Prohibited behavior includes harassing or causing distress or inconvenience to any other user, transmitting obscene or offensive content, or disrupting the normal flow of dialogue within the website.</p>
        
        <p><strong>4. Intellectual Property Rights</strong><br>
        All content, trademarks, and data on this website, including but not limited to software, databases, text, graphics, icons, hyperlinks, private information, designs, and agreements, are the property of or licensed to Sin Nam Medical Hall and as such are protected from infringement by local and international legislation and treaties.</p>
        
        <p><strong>5. Medical Advice Disclaimer</strong><br>
        The content on this website is provided for general information purposes only. It is not intended to replace consultation with a qualified medical professional. We do not provide medical advice, diagnosis, or treatment.</p>
        
        <p><strong>6. Appointments and Services</strong><br>
        By booking an appointment through our website, you agree to provide accurate and truthful information. We reserve the right to cancel or reschedule appointments as necessary. Our services are subject to availability and we do not guarantee that all services will be available at all times.</p>
        
        <p><strong>7. Privacy Policy</strong><br>
        Your privacy is important to us. Please refer to our Privacy Policy for information on how we collect, use, and protect your personal data.</p>
        
        <p><strong>8. Limitation of Liability</strong><br>
        To the fullest extent permitted by law, Sin Nam Medical Hall shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits or revenues, whether incurred directly or indirectly, or any loss of data, use, goodwill, or other intangible losses, resulting from (i) your use or inability to use the website; (ii) any unauthorized access to or use of our servers and/or any personal information stored therein.</p>
        
        <p><strong>9. Changes to Terms and Conditions</strong><br>
        We may revise these terms and conditions from time to time. The revised terms will apply to the use of our website from the date of publication of the revised terms on the website. Please check this page regularly to ensure you are familiar with the current version.</p>
        
        <p><strong>10. Governing Law</strong><br>
        These terms and conditions are governed by and construed in accordance with the laws of Singapore. Any disputes relating to these terms and conditions shall be subject to the exclusive jurisdiction of Singapore Courts.</p>
        
        <p><strong>11. Contact Information</strong><br>
        If you have any questions about these terms and conditions, please contact us at:<br>
        Sin Nam Medical Hall<br>
        #01-101 Yishun Street 71, Block 729, Singapore 760729<br>
        support.sinnam@gmail.com<br>
        +62 6257 0881</p>
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

    <script>
        // Get the modal
        var modal = document.getElementById("termsModal");

        // Get the button that opens the modal
        var btn = document.getElementById("termsLink");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
