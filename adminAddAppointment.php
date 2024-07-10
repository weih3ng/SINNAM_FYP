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
    $doctor_id = 1; // Fixed doctor ID

    // Set relationship_type to empty string if booking is for self
    if ($is_for_self == '1') {
        $relationship_type = '';
    } 

    // Insert the new appointment into the database
    $sql = "INSERT INTO appointments (patients_id, doctor_id, date, time, is_for_self, relationship_type, medical_condition) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'iississ', $patients_id, $doctor_id, $date, $time, $is_for_self, $relationship_type, $medical_condition);

    if (mysqli_stmt_execute($stmt)) {
        // Set success message
        $_SESSION['success_message_appointment'] = "New appointment added!";
        header("Location: manageAppointments.php");
        exit();
    } else {
        $error_message = "Error: " . mysqli_error($link);
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
        html, body {
            background-color: #F1EDE2;  /* ensure the background color covers the entire viewport */
        }

        .admin-panel-container {
            display: flex; /* makes the container a flex container so that items are well-aligned */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            padding: 50px 20px;
            min-height: calc(100vh - 150px);  /* ensure the container takes at least the full viewport height */
        }

        h2{
            text-align: center;
        }

        .form-container {
            background-color: #ffffff;
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
        }

        .form-container input,
        .form-container select {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 30px;
            border: 1px solid #ccc;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="date"],
        .form-container input[type="time"],
        .form-container select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #DC3545;
            border-radius: 20px;
            background-color: #F8D7DA;

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
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <a class="navbar-brand text-dark">
            <img src="images/logo.jpeg" alt="logo" class="logo">
        </a>
        <div class="navbar-links">
            <a href="manageUsers.php">Manage Users</a>
            <a href="manageAppointments.php">Manage Appointments</a>
        </div>

    <!-- Sign Up & Login Button -->



    <?php if (isset($_SESSION['username']) && ($_SESSION['username'] === 'doctor' || $_SESSION['username'] === 'admin')): ?>
            <p style='margin-top: 17px;'>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <?php else: ?>
            <p style='margin-top: 17px;'>Welcome, <a href='userProfile.php' style='text-decoration: underline; color: white;'><?php echo htmlspecialchars($_SESSION['username']); ?></a>!</p>
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

    <!-- Admin Panel Container -->
    <div class="admin-panel-container">
        <h1><i class="far fa-calendar-check"></i> Add New Appointment</h1>
        <div class="form-container">
            <form action="adminAddAppointment.php" method="POST">
                <label for="patients_id" class="required-label">Booking Name:</label>
                <select id="patients_id" name="patients_id" required>
                <option value="">Select Patient</option>
                <?php foreach ($patients as $id => $name): ?>
                    <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                <?php endforeach; ?>
                </select>
                <label for="date" class="required-label">Date:</label>
                <input type="date" id="date" name="date" required>
                <label for="time" class="required-label">Time:</label>
                <input type="time" id="time" name="time" required>
                <label for="medical_condition" class="required-label">Medical Condition:</label>
                <input type="text" id="medical_condition" name="medical_condition" required>
                <label for="is_for_self" class="required-label">Booking for:</label>
                <div>
                    <input type="radio" id="is_for_self_myself" name="is_for_self" value="1" required>
                    <label for="is_for_self_myself">Myself</label>
                    <input type="radio" id="is_for_self_family" name="is_for_self" value="0" required>
                    <label for="is_for_self_family">Family</label>
                </div>
                <div id="family_info" style="display: none;">
                    <label for="relationship_type" class="required-label">Relationship Type:</label>
                    <select id="relationship_type" name="relationship_type" required>
                        <option value="spouse">Spouse</option>
                        <option value="child">Child</option>
                        <option value="parent">Parent</option>
                        <option value="other">Other</option>
                    </select>
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
            })
        });

        // Disable Sundays and Mondays in the date picker
        document.getElementById('date').addEventListener('input', function(e) {
            var day = new Date(this.value).getUTCDay();
            if (day === 0 || day === 1) {
                alert('Booking on Sunday and Monday is not allowed. Please select another date.');
                this.value = '';
            }
        });
    </script>
</body>
</html>
