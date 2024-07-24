<?php
session_start();

include 'dbfunctions.php';

// Handle appointment form submission (Add)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['patients_id'])) {
    // Get form data
    $patients_id = $_POST['patients_id'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $medical_condition = $_POST['medical_condition'] ?? '';
    $is_for_self = $_POST['is_for_self'] ?? '';
    $relationship_type = $_POST['relationship_type'] ?? '';
    $family_name = $_POST['family_name'] ?? '';
    $doctor_id = 1; // Fixed doctor ID
    $admin_id = 1; //Fixed admin ID

    // Set relationship_type and family_name to empty string if booking is for self
    if ($is_for_self == '1') {
        $relationship_type = '';
        $family_name = '';
    } 

    // Check if the date and time are already booked
    $check_sql = "SELECT COUNT(*) FROM appointments WHERE date = ? AND time = ?";
    $stmt = mysqli_prepare($link, $check_sql);
    if ($stmt === false) {
        die('mysqli error: ' . mysqli_error($link));
    }
    mysqli_stmt_bind_param($stmt, 'ss', $date, $time);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    if ($count > 0) {
        $_SESSION['error_message'] = "The date and time slot has been booked. Please choose another date and time slot.";
    } else {
    // Insert the new appointment into the database
    $sql = "INSERT INTO appointments (patients_id, doctor_id, admin_id, date, time, is_for_self, relationship_type, family_name, medical_condition) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'iiississs', $patients_id, $doctor_id, $admin_id, $date, $time, $is_for_self, $relationship_type, $family_name, $medical_condition);

    if (mysqli_stmt_execute($stmt)) {
        // Set success message
        $_SESSION['success_message_appointment'] = "New appointment added!";
        header("Location: adminManageAppointments.php");
        exit();
    } else {
        $error_message = "Error: " . mysqli_error($link);
        }
    }
}

// Fetching patients data
$patients_sql = "SELECT patients_id, name FROM patients";
$patients_result = mysqli_query($link, $patients_sql);
$patients = [];
if ($patients_result && mysqli_num_rows($patients_result) > 0) {
    while ($row = mysqli_fetch_assoc($patients_result)) {
        $patients[$row['patients_id']] = $row['name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Add New Appointment</title>
    <style>
/* General styling for the form */
html, body {
    background-color: #F1EDE2;
}

.admin-panel-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #F1EDE2;
    padding: 50px 20px;
    min-height: calc(100vh - 150px);
}

h2 {
    text-align: center;
}

.form-container {
    background-color: #DECFBC;
    padding: 20px;
    border-radius: 30px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 50%;
    margin-bottom: 40px;
    align-items: center;
    margin: 0 auto;
}

.form-container form {
    display: flex;
    flex-direction: column;
    width: 100%;
}

.form-container label {
    margin-bottom: 5px;
    font-weight: bold;
}

.form-container label.required-label::before {
    content: " *";
    color: red;
    margin-left: 5px;
    padding-right: 10px;
}

.form-container input,
.form-container select,
.form-container textarea {
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 30px;
    border: 1px solid #ccc;
}

.form-container .button-container {
    text-align: center; 
}

.form-container button {
    background-color: #80352F;
    color: white;
    margin: 5px;
    padding: 10px 50px;
    font-size: 16px;
    width: 180px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-weight: bold;
}

.form-container button:hover {
    background-color: #6b2c27;
}

/* Additional CSS styling for navigation bar */
.navbar-links a.current {
    position: relative;
    color: white;
}

.navbar-links a.current:after {
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: -10px;
    height: 3px;
    background-color: white;
    border-radius: 2px;
}

    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <a class="navbar-brand text-dark">
            <img src="images/logo.jpeg" alt="logo" class="logo">
        </a>
        <div class="navbar-links">
            <a href="adminManageUsers.php">Manage Users<span class="underline"></span></a>
            <a href="adminManageAppointments.php">Manage Appointments<span class="underline"></span></a>
        </div>

    <!-- Sign Up & Login Button -->
    <?php
    if (isset($_SESSION['username'])) { 
    // Display 'Welcome, username'
    echo "<p style='margin-top: 17px;'>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</p>";
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

        <!-- Admin Panel Container -->
        <div class="admin-panel-container">
        <h1>Add New Appointment</h1>
        <div class="form-container">

    <form action="adminAddAppointment.php" method="POST">
        <label for="patients_id" class="required-label">
            <i class="fas fa-user"></i> Booking Name:</label>
        <select id="patients_id" name="patients_id" style="width: 700px;" required>
            <option value="">Select Patient</option>
            <?php foreach ($patients as $id => $name): ?>
                <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
            <?php endforeach; ?>
        </select>
        
        <label for="date" class="required-label">
            <i class="fas fa-calendar-alt"></i> Date:</label>
        <input type="date" id="date" name="date" style="width: 680px;" required>
        
        <label for="time" class="required-label">
            <i class="far fa-clock"></i> Time:</label>
        <select id="time" name="time" style="width: 700px;" required></select>
        
        <label for="medical_condition" class="required-label">
            <i class="fas fa-laptop-medical"></i> Medical Condition:</label>
        <textarea id="medical_condition" name="medical_condition" style="width: 680px; height: 150px;" required rows="4" style="resize: none;"></textarea>
        
        <label for="is_for_self" class="required-label">
            <i class="fas fa-users"></i> Booking for:</label>
        <div>
            <input type="radio" id="is_for_self_myself" name="is_for_self" value="1" required>
            <label for="is_for_self_myself">Myself</label>
            <input type="radio" id="is_for_self_family" name="is_for_self" value="0" required>
            <label for="is_for_self_family">Family</label>
        </div>
        <br>
        <div id="family_info" style="display: none;">
            <label for="relationship_type" class="required-label">
                <i class="fas fa-people-arrows"></i> Relationship Type:</label>
            <select id="relationship_type" name="relationship_type" style="width: 700px;">
                <option value="spouse">Spouse</option>
                <option value="child">Child</option>
                <option value="parent">Parent</option>
                <option value="other">Other</option>
            </select>
            <br>
            <label for="family_name" class="required-label">
                <i class="fas fa-user-tag"></i> Family member's name:</label>
            <input type="text" id="family_name" name="family_name" style="width: 680px;">
        </div>
        
        <div class="button-container">
            <button type="submit">Add Appointment</button>
        </div>
    </form>
</div>

    </div>

    <!-- Footer -->
    <footer>
            <img src="images/logo.jpeg" alt="logo" class="logo">
        <div>
            @ 2024 Sin Nam Medical Hall All Rights Reserved
        </div>
        <div class="social-media">
            <span style="margin-right: 10px;">Follow us</span>
            <a href="https://www.facebook.com/profile.php?id=167794019905102&_rdr"><i class="fa-brands fa-facebook"></i></a>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Display relationship type field if it is family, or else hide field for add form
        document.querySelectorAll('input[name="is_for_self"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const familyInfo = document.getElementById('family_info');
                if (this.value === '0') {
                    familyInfo.style.display = 'block';
                } else {
                    familyInfo.style.display = 'none';
                }
            });
        });

        // Disable Sundays and Mondays in the date picker
        document.getElementById('date').addEventListener('input', function(e) {
            var day = new Date(this.value).getUTCDay();
            if (day === 0 || day === 1) {
                alert('Booking on Sunday and Monday is not allowed. Please select another date.');
                this.value = '';
            } else {
                populateTimeSlots(new Date(this.value));
            }
        });

        // Restrict past dates in the date picker
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date').setAttribute('min', today);

        // Populate time slots based on the selected date
        function populateTimeSlots(selectedDate) {
            var day = selectedDate.getUTCDay();
            var timeSelect = document.getElementById('time');
            timeSelect.innerHTML = '';

            var startTime, endTime;

            if (day === 6) { // Saturday
                startTime = 10.5; // 10:30 AM
                endTime = 16.25; // 4:15 PM
            } else { // Tuesday to Friday
                startTime = 11; // 11:00 AM
                endTime = 16.25; // 4:15 PM
            }

            for (var time = startTime; time <= endTime; time += 0.25) {
                var hour = Math.floor(time);
                var minutes = (time - hour) * 60;
                var timeString = ('0' + hour).slice(-2) + ':' + ('0' + minutes).slice(-2) + ':00';

                var option = document.createElement('option');
                option.value = timeString;
                option.text = (hour % 12 || 12) + ':' + ('0' + minutes).slice(-2) + ' ' + (hour < 12 ? 'AM' : 'PM');
                timeSelect.appendChild(option);
            }

            // If today's date is selected, remove past time slots
            var today = new Date();
            if (selectedDate.toDateString() === today.toDateString()) {
                var currentTime = today.getHours() + ':' + ('0' + today.getMinutes()).slice(-2) + ':00';
                var options = timeSelect.options;
                for (var i = options.length - 1; i >= 0; i--) {
                    if (options[i].value < currentTime) {
                        timeSelect.remove(i);
                    }
                }
            }
        }

        // Display error message as an alert
        <?php if (isset($_SESSION['error_message'])): ?>
            alert('<?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>');
        <?php endif; ?>
    </script>
</body>
</html>
