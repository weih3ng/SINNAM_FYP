<?php
session_start();

include 'dbfunctions.php';

// Check if the user is logged in (joc)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Check if 'id' parameter is provided in the URL
if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    // Prepare query to retrieve appointment details
    $query = "SELECT * FROM appointments WHERE appointment_id = $appointment_id";
    $result = mysqli_query($link, $query);

    if ($result) {
        // Fetch appointment details
        $appointment = mysqli_fetch_assoc($result);

        if ($appointment) {
            // Extract appointment details
            $appointmentDate = $appointment['date'];
            $appointmentTime = $appointment['time'];
            // Additional fields to retrieve from the appointment table
            // $queueNumber = $appointment['queue_number'];
            // $status = $appointment['booking_status'];
        } else {
            // No appointment found with the given ID
            echo "Appointment not found";
            exit; // Stop further execution if appointment not found
        }
    } else {
        // Error querying the database
        echo "Error: " . mysqli_error($link);
        exit; // Stop further execution on database error
    }

    // Close connection
    mysqli_close($link);
} else {
    // Redirect to viewAppointment.php if 'id' parameter is not provided
    header("Location: viewAppointment.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">  <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome --> 
    <link rel="stylesheet" href="style.css"> <!-- External stylesheet for navigation bar and footer -->
    <title>Delete Appointment Page</title>
    <style>
        .delete-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            padding: 50px 20px;
            height: calc(100vh - 100px);
        }

        .delete-box {
            background-color: #DECFBC;
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .delete-box h1 {
            margin-bottom: 40px;
            text-align:center;
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
        .form-group input[type="date"], 
        .form-group input[type="time"] {
            display: block;
            width: 250px;
            padding: 10px;
            border-radius: 30px;
            border: 1px solid #ccc;
            flex: 1;
        }

        .delete-box .btn {
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

        .delete-box .btn:hover {
            background-color: #6b2c27;
        }


        /* Styles for read-only and disabled input fields */
        input[readonly]
        {
            background-color: #edede9;
            color: #686868; 
            cursor: not-allowed; 
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

    <!-- Delete Appointment Container -->
    <div class="delete-container">
        <div class="delete-box">
            <h1>Delete Appointment</h1>
            <form method="post" action="doDeleteAppointment.php">
                <div class="form-group">
                    <label for="id">Queue Number:</label>
                    <input type="text" id="id" name="appointment_id" value="<?php echo $appointment_id; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo $appointmentDate; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" value="<?php echo $appointmentTime; ?>" readonly>
                </div>
                <br>
                <button type="submit" class="btn">Confirm Deletion</button>
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
