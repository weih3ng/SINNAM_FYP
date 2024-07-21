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
            height: calc(180vh - 120px);
        }

        .left-decoration img {
            max-width: 730px;
            margin-left: -120px;
            margin-top: 35px;
        }

        .register-form-container {
            background-color: #DECFBC;
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
        .register-form-container input[type="number"],
        .register-form-container input[type="email"],
        .register-form-container input[type="password"],
        .register-form-container input[type="contactnumber"],
        .register-form-container input[type="date"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #DC3545;
            border-radius: 20px;
            background-color: white;

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

        .register-form-container label.required-label::before {
            content: " *";
            color: red;
            margin-left: 5px;
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

        <!-- Register Container -->
        <div class="container">
        <div class="left-decoration">
            <img src="images/2.png" alt="Decoration">
        </div>
        <div class="register-form-container">
            <h1>Create New Account</h1>
            <p>Already a member? <a href="login.php">Log in</a></p>
            <form action="doSignUp.php" method="POST">
                <label for="idName" class="required-label">
                    <i class="fa-solid fa-user" style="color: #949494;"></i> Name
                </label>
                <input id="idName" type="text" name="name" required>
                <label for="idUsername" class="required-label">
                    <i class="far fa-user" style="color: #949494;"></i> Username
                </label>
                <input id="idUsername" type="text" name="username" required>

                <label for="idEmail" class="required-label">
                    <i class="fa-solid fa-envelope" style="color: #949494;"></i> Email
                </label>
                <input id="idEmail" type="email" name="email" required>

                <label for="idcontactnumber" class="required-label">
                    <i class="fa-solid fa-phone" style="color: #949494;"></i> Contact Number
                </label>
                <input id="idcontactnumber" type="contactnumber" name="contactnumber" required>

                <label for="idDob" class="required-label">
                    <i class="fa-solid fa-calendar-alt" style="color: #949494;"></i> Date of Birth
                </label>
                <input id="idDob" type="date" name="dob" required>

                <label for="idPassword" class="required-label">
                    <i class="fa-solid fa-lock" style="color: #949494;"></i> Password
                </label>
                <input id="idPassword" type="password" name="password" required>

                <div class="gender-container">
                    <label class="gender-label required-label">
                        <i class="fa-solid fa-venus-mars" style="color: #949494;"></i> Gender
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
                    By clicking "SIGN UP", I acknowledge that I have read, understood and agree that I am bound by the <a href="#" id="termsLink">Account Terms of Use</a>.</p>
            </form>
        </div>
        </div>
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
                <span style="margin-right: 10px;">Follow us</span> <!-- Added a span to apply margin -->
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
        document.addEventListener("DOMContentLoaded", function() {
            var dobInput = document.getElementById("idDob");

            // Get today's date
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // January is 0!
            var yyyy = today.getFullYear();
            var todayDate = yyyy + '-' + mm + '-' + dd;

            dobInput.setAttribute("max", todayDate); // Set maximum date to today

            dobInput.addEventListener("change", function() {
                var selectedDate = new Date(this.value);
                if (selectedDate > today) {
                    alert("Please select a date that is not in the future.");
                    this.value = ""; // Clear the input value
                }
            });
        });
    </script>
</body>
</html>
