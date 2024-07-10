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
    <title>Contact Us Page</title>
    <style>
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            padding: 50px 20px;
        }
        .contact-form-container {
            padding: 30px 40px;
            width: 600px;
            margin-bottom: 50px;
        }

        .contact-form-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .contact-form-container label {
            display: block;
            margin-bottom: 5px;
        }

        .contact-form-container .input-group {
            display: flex;
            gap: 20px;
        }

        .contact-form-container input[type="text"],
        .contact-form-container input[type="email"],
        .contact-form-container input[type="phone"],
        .contact-form-container textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 20px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 8px rgba(169, 169, 169, 0.6);
        }

        .contact-form-container textarea {
            height: 100px;
        }

        .contact-form-container .btn {
            display: block;
            width: 150px;
            padding: 10px;
            background-color: #80352F;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
        }

        .contact-form-container .btn:hover {
            background-color: #6b2c27;
        }

        .contact-details {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            height: 100px;
        }

        .contact-details div {
            background-color: #80352F;
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: transform 0.2s; /* Smooth transition for hover effect */
        }

        .contact-details div:hover {
            transform: scale(1.15); /* Slightly enlarge the box on hover */
        }

        .contact-details div a {
            color: white;
            text-decoration: none;
        }

        iframe {
            border: none;
            width: 600px;
            height: 450px;
            border-radius: 15px;
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
            <?php if (isset($_SESSION['username'])): ?>
            <a href="viewAppointment.php">View Appointment</a>
            <?php else: ?>
                <?php endif; ?>
            <a href="contact.php">Contact Us</a>
        </div>

        <!-- Sign Up & Login Button -->


        <?php if (isset($_SESSION['username'])): ?>
            <p style='margin-top: 17px;'>Welcome, <a href='userProfile.php' style='text-decoration: underline; color: white;'><?php echo htmlspecialchars($_SESSION['username']); ?></a>!</p>


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

        <!-- Contact Us Container -->
        <div class="container">
            <div class="contact-form-container">
                <h1>Contact Us</h1>
                <form id="contactForm">
                    <div class="input-group">
                        <input id="name" type="text" name="name" placeholder="Name *" required>
                        <input id="phone" type="phone" name="phone" placeholder="Phone *" required>
                    </div>
                    <label for="email"></label>
                    <input id="email" type="email" name="email"placeholder="Email *" required>
                    <label for="message"></label>
                    <textarea id="message" name="message"placeholder="Message *" required></textarea><span class="ipsFieldRow_required" style="margin-left: 10px;">ALL FIELDS ARE Required</span>
                    <button type="submit" class="btn">Submit</button> 
                </form>
                <div id="successMessage" style="display:none; color: red; margin-top: 20px;">
                Your contact form has been submitted successfully !
            </div>
            </div>
            <div class="contact-details">
                <div><b><i class="fa-solid fa-phone"></i>&nbsp; +65 6257 0881 </b></div>
                <div><b><i class="far fa-envelope"></i>&nbsp;<a href="mailto:sinnam@gmail.com">sinnam@gmail.com</a></b></div>
                <div><b><i class="fab fa-facebook"></i>&nbsp;<a href="https://www.facebook.com/profile.php?id=167794019905102&_rdr">Sin Nam Medical Hall</a></b></div>
            </div>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.5821468522818!2d103.82907987310031!3d1.4263036613292244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da14719291728d%3A0xdada9f87c45b6ac8!2sSin%20Nam%20Medical%20Hall%20Pte%20Ltd!5e0!3m2!1sen!2ssg!4v1717591901703!5m2!1sen!2ssg"></iframe>
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

    <!-- Java Script code for submission of contact form -->
    <script>
        document.getElementById("contactForm").addEventListener("submit", function(event) {
            event.preventDefault(); /* stops the form from submitting traditionally(reloading the page,send data to server)*/
            document.getElementById("successMessage").style.display = "block"; /*Display a success message*/
            document.getElementById("contactForm").reset();  /* Form fields will be cleared after submission*/
        });
    </script>
</body>
</html>
