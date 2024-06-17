<?php 
session_start(); // Start the session

include 'dbfunctions.php';

// Check if appointment ID is provided
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Fetch existing appointment data
    $query = "SELECT * FROM appointments WHERE appointment_id = $appointment_id";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    
    $row = mysqli_fetch_assoc($result);
    if (!empty($row)) {
        $appointmentDate = $row['date'];
        $appointmentTime = $row['time'];
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Update the appointment in the database
    $query = "UPDATE appointments SET date = '$date', time = '$time' WHERE appointment_id = $appointment_id";
    mysqli_query($link, $query) or die(mysqli_error($link));

    // Redirect to viewAppointment.php after update
    header("Location: viewAppointment.php");
    exit();
}

// Close database connection
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
    <title>Edit Appointment Page</title>
    <style>
        .edit-container {
            display: flex; /*lays out the flex items in a column, vertically from top to bottom */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            padding: 50px 20px;
            height: calc(100vh - 100px); /* Adjust height to fit within the viewport */
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
            text-align:center;
        }

        .form-group {
            display: flex; /* Makes the container a flex container so that items are well-aligned */
            align-items: center;
            margin-bottom: 15px;
        }

        .form-group label {
            margin-bottom: 10px;
            font-weight: bold;
            width: 80px; 
            text-align: right;
            padding-right: 30px; /* Added padding for spacing */
        }

        .form-group input[type="text"], 
        .form-group input[type="date"], 
        .form-group input[type="time"] {
            display: block; /*starts on a new line and takes up the full width available */
            width: 250px; /* Adjust the width to be longer */
            padding: 10px;
            border-radius: 30px;
            border: 1px solid #ccc;
            flex: 1; /* take up available space within the .form-group container*/
        }

        .edit-box .btn {
            display: inline-block; /*sit inline with any other inline or inline-block elements next to it */
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
            <a href="contact.php">Contact Us</a>
        </div>

        <!-- Sign Up & Login Button -->
        <a class="nav-custom" href="signUp.php">
            <i class="fa-solid fa-user"></i> Sign Up
        </a>

        <?php if (isset($_SESSION['username'])) { ?>
            <a class="nav-custom" href="logout.php">
                <i class="fa-solid fa-right-to-bracket"></i> Logout
            </a>
        <?php } else { ?>
            <a class="nav-custom" href="login.php">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </a>
        <?php } ?>
    </div>

    <!-- Edit Appointment Container -->
    <div class="edit-container">
        <div class="edit-box">
            <h1>Edit Appointment</h1>
            <form method="post" action="editAppointment.php?id=<?php echo $appointment_id; ?>">
                <div class="form-group">
                    <label for="id">Appt ID:</label>
                    <input type="text" id="id" name="id" value="<?php echo $appointment_id; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo $appointmentDate; ?>">
                </div>
                <div class="form-group">
                    <label for="time">Time:</label>
                    <input type="time" id="time" name="time" value="<?php echo $appointmentTime; ?>">
                </div>
                <br>
                <button type="submit" class="btn">Confirm Edit</button>
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
