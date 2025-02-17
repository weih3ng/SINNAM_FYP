<?php
session_start();

include 'dbfunctions.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Get the user's patient ID from the session
$patients_id = $_SESSION['patients_id'];

// Prepare query to retrieve user details
$query = "SELECT * FROM patients WHERE patients_id = $patients_id";
$result = mysqli_query($link, $query);

if ($result) {
    // Fetch user details
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Extract user details
        $username = $user['username'];
        $name = $user['name'];
        $email = $user['email'];
        $dob = $user['dob'];
        $gender = $user['gender'];
        $contactnumber = $user['contactnumber'];
        $password = $user['password'];
    } else {
        // No user found with the given ID
        echo "User not found";
        exit; // Stop further execution if user not found
    }
} else {
    // Error querying the database
    echo "Error: " . mysqli_error($link);
    exit; // Stop further execution on database error
}

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">  <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome --> 
    <link rel="stylesheet" href="style.css"> <!-- External stylesheet for navigation bar and footer -->
    <title>Edit Profile</title>
    <style>
        .edit-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            padding: 50px 20px;
            height: calc(100vh - 100px);
        }

        .edit-box {
            background-color: #DECFBC;
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .edit-box h1 {
            margin-bottom: 40px;
            text-align: center;
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            margin-bottom: 10px;
            font-weight: bold;
            width: 80px; 
            text-align: right;
            padding-right: 30px;
        }

        .form-group input[type="text"],
        .form-group input[type="contactnumber"], 
        .form-group input[type="email"], 
        .form-group input[type="date"], 
        .form-group input[type="number"] {
            display: block;
            width: 250px;
            padding: 10px;
            border-radius: 30px;
            background-color: #ffffff; 
            border: 2px solid black;
            flex: 1;
        }

        .edit-box .btn {
            display: inline-block;
            padding: 10px 50px;
            margin: 5px;
            background-color: #80352F;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .edit-box .btn:hover {
            background-color: #6b2c27;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 330px; 
        }

        #password {
            width: 100%;
            padding: 10px;
            padding-right: 40px; 
            border-radius: 30px;
            border: 2px solid black;
            background-color: #ffffff;
            flex-grow: 1;
        }

        .eye-toggle {
            position: absolute;
            right: 10px; 
            cursor: pointer;
            color: #686868; 
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

    <!-- Edit Profile Container -->
    <div class="edit-container">
        <div class="edit-box">
            <h1>Edit/Delete Profile</h1>
            <form method="post" action="doEditUserProfile.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                </div>
                
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" max="">
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <div class="gender-options">
                        <input type="radio" id="male" name="gender" value="Male" <?php if ($gender == 'Male') echo 'checked'; ?>>
                        <label for="male">Male</label>

                        <input type="radio" id="female" name="gender" value="Female" <?php if ($gender == 'Female') echo 'checked'; ?>>
                        <label for="female">Female</label>

                    </div>
                </div>

                <div class="form-group">
                    <label for="contactnumber">Contact Number:</label>
                    <input type="contactnumber" id="contactnumber" name="contactnumber" maxlength="8" pattern="\d{8}" value="<?php echo htmlspecialchars($contactnumber); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
                        <span class="eye-toggle fa fa-eye" onclick="togglePasswordVisibility()"></span>
                    </div>
                </div>

                <br>
                <button type="submit" class="btn" onclick="confirmEdit(event)">Save Changes</button>
                <button type="button" class="btn" onclick="confirmDelete()">Delete Profile</button>
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

    <script>

        // Function to confirm edit profile
        function confirmEdit() {
            if (confirm("Are you sure you want to edit your profile?")) {
                window.location.href = 'doEditUserProfile.php';
            }
        }
        function confirmDelete() {
            if (confirm("Are you sure you want to delete your profile? This action cannot be undone.")) {
                window.location.href = 'doDeleteUserProfile.php';
            }
        }
        

        // Function to toggle password visibility
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.querySelector(".eye-toggle");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }

        window.onload = function() {
            // Set the date to maximum by allowing the date of birth to today's date
            const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
            document.getElementById('dob').setAttribute('max', today); // Set the maximum date of birth to today's date

            // Check whether the username is already taken or not
            const urlParams = new URLSearchParams(window.location.search); // Get the URL parameters
            if (urlParams.has('error') && urlParams.get('error') === 'username_taken') { // Check if the error parameter is username_taken
                alert('Username is already taken.');
            }
            if (urlParams.has('success') && urlParams.get('success') === 'profile_updated') { // Check if the success parameter is profile_updated
                alert('Profile updated successfully.');
            }
        }

        // Function to confirm edit profile (alert box)
        function confirmEdit(event) {
            if (!confirm("Are you sure you want to edit your profile?")) {
                event.preventDefault(); // Prevent the form from submitting if the user click on cancel button
            }
        }

    </script>

</body>
</html>
