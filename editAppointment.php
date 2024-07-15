<?php 
session_start(); // Start the session

include 'dbfunctions.php';

// Check if the user is logged in (joc)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Check if appointment ID is provided (joc)
if (isset($_GET['appointment_id'])) { // Get from viewAppointment.php (joc)
    $appointment_id = $_GET['appointment_id']; // Get from viewAppointment.php (joc)

    // Fetch existing appointment data
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

// Handle form submission (joc)
// Handle form submission (joc)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $is_for_self = ($_POST['booking_for'] === 'self') ? 1 : 0;
    $relationship_type = ($is_for_self == 0) ? $_POST['relationship_type'] : null;
    $family_name = $is_for_self == 0 ? $_POST['family_name'] : null;
    $medical_condition = $_POST['medical_conditions'];

    // Validate date and time
    $currentDateTime = new DateTime();
    $selectedDateTime = new DateTime("$date $time");

    if ($selectedDateTime < $currentDateTime) {
        // Redirect with an error message if trying to set past time
        header("Location: editAppointment.php?appointment_id=$appointment_id&error=past_time");
        exit();
    }

    // Update the appointment in the database
    $query = "UPDATE appointments SET date = ?, time = ?, is_for_self = ?, relationship_type = ?, family_name = ?, medical_condition = ? WHERE appointment_id = ?";
    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param($stmt, "ssisssi", $date, $time, $is_for_self, $relationship_type, $family_name, $medical_condition, $appointment_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Redirect back to viewAppointment.php after update
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


        /* Styles for read-only and disabled input fields (joc) */
        input[readonly] {
            background-color: #e0e0e0;
            color: #686868; 
            cursor: not-allowed; 
        }

        /* Styles for editable fields */
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
            width: 150px; /* Ensure all labels have the same width */
            text-align: right;
        }

        /* Specific styles for relationship and family name fields for consistency */
        #relationship_type, #family_name {
            display: inline-block;
            width: auto;
            flex-grow: 1; /* Allows the input to fill the space */
        }
        .form-group select {
    display: block;
    width: 250px;
    padding: 10px;
    border-radius: 30px; /* Rounded corners */
    border: 1px solid #ccc;
    flex: 1;
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

    <!-- Edit Appointment Container -->
    <div class="edit-container">
        <div class="edit-box">
            <h1>Edit Appointment</h1>
            <form method="post" action="editAppointment.php?appointment_id=<?php echo $appointment_id; ?>">
                <div class="form-group">
                    <label for="id">Queue Numbers:</label>
                    <input type="text" id="id" name="appointment_id" value="<?php echo $appointment_id; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" value="<?php echo $appointmentDate; ?>" min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
    <label for="time">Time:</label>
    <select id="time" name="time">
        <!-- Options will be dynamically populated using JavaScript -->
    </select>
</div>


                <!-- Booking for self or family member and medicial condition (joc) -->
                <div class="form-group">
                    <label>Booking for:</label>
                    <div>
                        <input type="radio" id="for_self" name="booking_for" value="self" <?php echo $is_for_self ? 'checked' : ''; ?>>
                        <label for="for_self">Myself</label>
                        <input type="radio" id="for_family" name="booking_for" value="family" <?php echo !$is_for_self ? 'checked' : ''; ?>>
                        <label for="for_family">Family Member</label>
                    </div>
                </div>
                <div class="form-group" id="family_info" style="display: none;">
                    <label for="relationship_type">Relationship Type:</label>
                    <select id="relationship_type" name="relationship_type">
                        <option value="spouse" <?php echo ($relationship_type == 'spouse') ? 'selected' : ''; ?>>Spouse</option>
                        <option value="child" <?php echo ($relationship_type == 'child') ? 'selected' : ''; ?>>Child</option>
                        <option value="parent" <?php echo ($relationship_type == 'parent') ? 'selected' : ''; ?>>Parent</option>
                        <option value="other" <?php echo ($relationship_type == 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group" id="family_name_group" style="display: none;"> <!-- This will be shown/hidden based on booking type (joc) -->
                    <label for="family_name">Family Member Name:</label>
                    <input type="text" id="family_name" name="family_name" value="<?php echo htmlspecialchars($family_name); ?>">
                </div>
                <div class="form-group">
                    <label for="medical-conditions">Medical Condition:</label>
                    <textarea id="medical-conditions" name="medical_conditions" rows="4"><?php echo $medical_condition; ?></textarea>
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

    <script>

        // Show/hide family member name based on booking type (joc)
        document.querySelectorAll('input[name="booking_for"]').forEach(input => {
            input.addEventListener('change', function() {
                const isFamily = document.getElementById('for_family').checked;
                document.getElementById('family_info').style.display = isFamily ? 'block' : 'none';
                document.getElementById('family_name_group').style.display = isFamily ? 'block' : 'none'; // Add this line
            });
        });

        // Initialize the display based on current selection when the page loads (joc)
        document.addEventListener('DOMContentLoaded', function() {
            const isFamily = document.getElementById('for_family').checked;
            document.getElementById('family_info').style.display = isFamily ? 'block' : 'none';
            document.getElementById('family_name_group').style.display = isFamily ? 'block' : 'none';
        });




        

        document.addEventListener('DOMContentLoaded', function() {
    const currentDate = new Date();
    const currentDateString = currentDate.toISOString().slice(0, 10); // Get current date in YYYY-MM-DD format
    const currentTime = currentDate.toTimeString().slice(0, 5); // Get current time in HH:mm format

    // Set min attribute for date input to prevent selecting past dates
    document.getElementById('date').min = currentDateString;

    // Function to update time options based on selected date
    function updateAvailableTimes() {
        const selectedDate = new Date(document.getElementById('date').valueAsDate);
        const selectedDay = selectedDate.getDay(); // 0 (Sunday) to 6 (Saturday)
        const selectedDateString = selectedDate.toISOString().slice(0, 10); // Selected date in YYYY-MM-DD format
        const selectedTimeInput = document.getElementById('time');

        // Clear existing options
        selectedTimeInput.innerHTML = '';

        // Set default range (11:00 AM to 4:30 PM with 15-minute intervals)
        let startTime;
        if (selectedDay === 6) { // Saturday
            startTime = new Date(selectedDateString + 'T10:30:00');
        } else {
            startTime = new Date(selectedDateString + 'T11:00:00');
        }
        const endTime = new Date(selectedDateString + 'T16:15:00');

        // Populate time options with 15-minute intervals
        const interval = 15; // 15 minutes interval
        let currentTime = startTime;

        while (currentTime <= endTime) {
            const option = document.createElement('option');
            option.value = currentTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            option.textContent = currentTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            selectedTimeInput.appendChild(option);

            currentTime.setMinutes(currentTime.getMinutes() + interval);
        }
    }

    // Initial setup on page load
    updateAvailableTimes();

    // Event listener for date change to update time options
    document.getElementById('date').addEventListener('change', updateAvailableTimes);
});






    
        


    </script>


</body>
</html>
