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
        .form-group input[type="email"], 
        .form-group input[type="date"], 
        .form-group input[type="number"] {
            display: block;
            width: 250px;
            padding: 10px;
            border-radius: 30px;
            border: 1px solid #ccc;
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

    <!-- Edit Profile Container -->
    <div class="edit-container">
        <div class="edit-box">
            <h1>Edit/Delete Profile</h1>
            <form method="post" action="doEditProfile.php">
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
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <input type="text" id="gender" name="gender" value="<?php echo htmlspecialchars($gender); ?>">
                </div>
                <div class="form-group">
                    <label for="contactnumber">Contact Number:</label>
                    <input type="text" id="contactnumber" name="contactnumber" value="<?php echo htmlspecialchars($contactnumber); ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>">
                </div>
                <br>
                <button type="submit" class="btn" onclick="confirmEdit()">Save Changes</button>
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
        function confirmEdit() {
            if (confirm("Are you sure you want to edit your profile?")) {
                window.location.href = 'doEditProfile.php';
            }
        }
        function confirmDelete() {
            if (confirm("Are you sure you want to delete your profile? This action cannot be undone.")) {
                window.location.href = 'doDeleteProfile.php';
            }
        }
    </script>
</body>
</html>
