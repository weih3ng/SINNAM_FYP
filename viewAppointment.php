<?php 
session_start(); // Start the session

include 'dbfunctions.php';

// Check if the user is logged in (joc)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$patients_id = $_SESSION['patients_id']; // Retrieve patient ID from session (joc)

// Retrieve appointments for the logged in patient (joc)
$query = "SELECT a.appointment_id, a.date, a.time, a.queue_number, a.is_for_self, a.relationship_type, a.medical_condition, p.name
        FROM appointments AS a
        INNER JOIN patients AS p ON a.patients_id = p.patients_id
        WHERE a.patients_id = ?";

if ($stmt = mysqli_prepare($link, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $patients_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $appointments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
} else {
    echo "ERROR: Could not prepare query: $query. " . mysqli_error($link);
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

    <title>View Appointment Page</title>

    <style>
        .view-appointment-container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            min-height: calc(110vh - 120px);
            padding: 20px;
            box-sizing: border-box;
        }

        .content-box {
            background-color: #DECFBC;
            border-radius: 80px;
            padding: 60px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 850px;
            width: 100%;
        }

        .content-box h1 {
            color: black;
            margin-bottom: 35px;
            font-size: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #80352F;
            background-color: #f4f1de;
        }

        th, td {
            border: 1px solid #80352F;
            padding: 8px;
            text-align: left;
            color: #333;
        }

        th {
            background-color: #80352F; 
            color: white; 
            font-size: 18px;
        }

        .action-buttons {
            padding: 5px;
        }

        .btn-edit, .btn-delete {
            flex: 1; 
            margin: 0 2px; 
        }

        .btn-edit {
            color: #4CAF50; 
        }

        .btn-edit:hover {
            color: #3E8E41; 
        }

        .btn-delete {
            color: #f44336; 
        }

        .btn-delete:hover {
            color: #c1121f; 
        }

        .btn-done {
            background-color: #80352F;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 50px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.1s ease;
            margin-top: 20px;
        }

        .btn-done:hover {
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


    <!-- View Appointment Container -->
    <div class="view-appointment-container">
        <div class="content-box">
            <h1>View Appointment</h1>


            <table>
            <thead>
                <tr>
                <th>Appointment ID</th>
                <th>Patient Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Medical Condition</th>
                <th>Self/Family</th>
                <th>Relationship Type</th>
                <th>Queue Number</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment) : ?>
                    <tr>
                    <td><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['medical_condition']); ?></td>
                    <td><?php echo $appointment['is_for_self'] ? 'Self' : 'Family'; ?></td>
                    <td><?php echo htmlspecialchars($appointment['relationship_type']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['queue_number']); ?></td>
                    <td class="action-buttons">
                        <a href="editAppointment.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="deleteAppointment.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-delete">Delete</a>
                    </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
            <a href="home.php">
                <button class="btn btn-done">Done</button>
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