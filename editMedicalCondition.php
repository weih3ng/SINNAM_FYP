<?php 
session_start(); // Start the session (Dep)

include 'dbfunctions.php';

// Check if the user is logged in (Dep)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Check if appointment ID is provided (Dep)
if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

// Fetching existing appointments (Dep)
    $query = "SELECT * FROM appointments WHERE appointment_id = $appointment_id";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    
    $row = mysqli_fetch_assoc($result);
    if (!empty($row)) {
        $appointmentDate = $row['date'];
        $appointmentTime = $row['time'];
        $is_for_self = $row['is_for_self'];
        $relationship_type = $row['relationship_type'];
        $medical_condition = $row['medical_condition'];
        $family_name = $row['family_name'];
    }
}

// Form Submission (Dep)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $medical_condition = $_POST['medical_conditions'];

// Updating appointments in database (Dep)
$query = "UPDATE appointments SET medical_condition = ? WHERE appointment_id = ?";
if ($stmt = mysqli_prepare($link, $query)) {
    mysqli_stmt_bind_param($stmt, "si", $medical_condition, $appointment_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);

    header("Location: http://localhost/SINNAM_FYP/viewAppointment.php?user_type=doctor");
    exit(); 

    } else {
        echo "Error updating record: " . mysqli_stmt_error($stmt);
    }
    } else {
        echo "Error preparing statement: " . mysqli_error($link);
    }
}

// Close Connection (Dep)
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
    <title>Edit Medical Condition Page</title>

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

        .form-group textarea {
            display: block;
            width: 250px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            resize: vertical; /* Allow Vertical Resizing (Dep) */
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

        /* Read-only and Disabled Input Fields (Dep)*/
        input[readonly] {
            background-color: #e0e0e0;
            color: #686868; 
            cursor: not-allowed; 
        }

        /* Editable Fields (Dep) */
        input[type="date"]:not([readonly]), 
        input[type="time"]:not([readonly]), 
        input[type="text"]:not([readonly]),
        textarea:not([readonly]),
        select:not([disabled]),
        input[type="radio"]:not([disabled]) {
            background-color: #ffffff; 
            border: 2px solid #80352F; 
        }

        .form-group select,
        .form-group input[type="text"] {
            padding: 8px;
        }

        .form-group label {
            width: 150px; 
            text-align: right;
        }

        /* Specific styles for relationship and family name fields for consistency (Dep) */
        #relationship_type, #family_name {
            display: inline-block;
            width: auto;
            flex-grow: 1; 
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
            <?php if (isset($_SESSION['username'])): ?>
            <a href="viewAppointment.php?user_type=doctor">View Appointment<span class="underline"></span></a>
            <?php endif; ?>
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

    <!-- Edit Medical Conditions Container -->
    <div class="edit-container">
        <div class="edit-box">
            <h1>Edit Medical Conditions</h1>
            <form method="post" action="editMedicalCondition.php?appointment_id=<?php echo $appointment_id; ?>">
                <div class="form-group">
                    <label for="id">Queue Numbers:</label>
                    <input type="text" id="id" name="appointment_id" value="<?php echo $appointment_id; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo $appointmentDate; ?>" min="<?php echo date('Y-m-d'); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="id">Time:</label>
                    <input type="text" id="time" name="time" value="<?php echo $appointmentTime; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Booking for:</label>
                    <div>
                        <input type="radio" id="for_self" name="booking_for" value="self" <?php echo $is_for_self ? 'checked' : ''; ?> disabled>
                        <label for="for_self">Myself</label>
                        <input type="radio" id="for_family" name="booking_for" value="family" <?php echo !$is_for_self ? 'checked' : ''; ?> disabled>
                        <label for="for_family">Family Member</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="medical_conditions">Medical Conditions:</label>
                    <textarea id="medical_conditions" name="medical_conditions" rows="4"><?php echo $medical_condition; ?></textarea>
                </div>
                <button  type="submit" class="btn" name="submit">Update</button>
                <a href="viewAppointment.php?user_type=doctor" class="btn">Back</a>
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