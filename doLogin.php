<?php
session_start(); // Starting the session 

include 'dbfunctions.php';

// Retrieving the email and password from POST request
$email = $_POST['email'];
$password = $_POST['password'];

$msg = "";

// Function to check user in a specific table
function check_user($link, $email, $password, $table) {
    $query = "SELECT * FROM $table WHERE email = ? AND Password = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_array($result);
    }
    return false;
}

// Check patients table
$user = check_user($link, $email, $password, 'patients');
$user_type = 'patient';

if (!$user) {
    // Check admins table
    $user = check_user($link, $email, $password, 'admins');
    $user_type = 'admin';
    header('Location: manageUsers.php'); // direct to admin panel page
}

if (!$user) {
    // Check doctors table
    $user = check_user($link, $email, $password, 'doctors');
    $user_type = 'doctor';
}

// If a user is found, set session variables and success message
if ($user) {
    $_SESSION['success_message'] = "Successfully logged in.";
    $_SESSION['username'] = $user['username'];
    $_SESSION['loggedin'] = true; // Set the logged in session variable to true
    if ($user_type === 'doctor') {
        header('Location: viewAppointment.php?user_type=doctor'); // Redirect doctor to view all appointments
    } else {
        $_SESSION['patients_id'] = $user['patients_id'];
        header('Location: viewAppointment.php?user_type=patient'); // Redirect patient to view their appointments
    }
} else {
    // If no user is found, set error message and redirect to login.php
    $_SESSION['error_message'] = "Invalid email or password. Please try again.";
    header('Location: login.php');
}

mysqli_close($link); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">  <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->  
    <link rel="stylesheet" href="style.css">
    <title>Login Successful Page</title>

    <style>


        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            min-height: calc(100vh - 120px);
            padding: 20px;
            box-sizing: border-box;
        }

        .content-box {
            background-color: #DECFBC;
            border-radius: 80px;
            padding: 150px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .content-box h1 {
            color: black;
            margin-bottom: 30px;
            font-size: 40px;
        }

        .content-box p {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .content-box button {
            background-color: #80352F;
            color: white;
            border: none;
            border-radius: 30px;
            padding: 10px 50px;
            cursor: pointer;
            font-weight: bold;
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
    <div class="container">
        <div class="content-box">
            <h1>Login Successful!</h1><br>
            <p>You have successfully logged in.</p><br>
            <a href="home.php">
                <button>Home</button>
            </a>
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