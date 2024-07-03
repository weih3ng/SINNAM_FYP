<?php
session_start();

include 'dbfunctions.php';

// Initialize variables for success messages
$success_message_appointment = "";

// Handle appointment deletion
if (isset($_GET['delete_id'])) {
    $appointment_id = $_GET['delete_id'];

    // Delete the appointment from the database
    $sql = "DELETE FROM appointments WHERE appointment_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $appointment_id);

    if (mysqli_stmt_execute($stmt)) {
        // Success message
        $_SESSION['success_message_appointment'] = "Appointment deleted successfully!";
    } else {
        // Error message
        $_SESSION['error_message_appointment'] = "Error: " . mysqli_error($link);
    }

    // Redirect back to manageAppointments.php
    header("Location: manageAppointments.php");
    exit();
}

// Handle appointment form submission (Add and Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['patients_id'])) {
    // Get form data
    $patients_id = $_POST['patients_id'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $queue_number = $_POST['queue_number'] ?? '';
    $medical_condition = $_POST['medical_condition'] ?? '';
    $is_for_self = $_POST['is_for_self'] ?? '';
    $relationship_type = $_POST['relationship_type'] ?? '';
    $doctor_id = 1; // Fixed doctor ID

    // Set relationship_type to empty string if booking is for self
    if ($is_for_self == '1') {
        $relationship_type = '';
    } 

        if (isset($_POST['appointment_id'])) {
            // Update existing appointment
            $appointment_id = $_POST['appointment_id'];
            $sql = "UPDATE appointments SET patients_id = ?, doctor_id = ?, date = ?, time = ?, queue_number = ?, is_for_self = ?, relationship_type = ?, medical_condition = ? WHERE appointment_id = ?";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, 'iissiissi', $patients_id, $doctor_id, $date, $time, $queue_number, $is_for_self, $relationship_type, $medical_condition, $appointment_id);
        } else {
            // Insert the new appointment into the database
            $sql = "INSERT INTO appointments (patients_id, doctor_id, date, time, queue_number,is_for_self, relationship_type, medical_condition) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, 'iissiiss', $patients_id, $doctor_id, $date, $time, $queue_number, $is_for_self, $relationship_type, $medical_condition);
        }

        if (mysqli_stmt_execute($stmt)) {
            // Set success message
            $success_message_appointment = isset($_POST['appointment_id']) ? "Appointment updated successfully!" : "New appointment added!";
        } else {
            $success_message_appointment = "Error: " . mysqli_error($link);
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

// Fetch appointments data
$appointments_sql = "SELECT a.appointment_id, a.patients_id, a.doctor_id, a.date, a.time, a.queue_number, a.is_for_self,
        a.relationship_type, a.medical_condition, p.name
        FROM appointments AS a
        INNER JOIN patients AS p ON a.patients_id = p.patients_id";
$appointments_result = mysqli_query($link, $appointments_sql);

// Count total appointments(statistics)
$total_appointments_sql = "SELECT COUNT(*) as total_appointments FROM appointments";
$total_appointments_result = mysqli_query($link, $total_appointments_sql);
$total_appointments_row = mysqli_fetch_assoc($total_appointments_result);
$total_appointments = $total_appointments_row['total_appointments'];
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
    <title>Manage Appointments</title>
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
            min-height: calc(100vh - 150px); /* ensure the container takes at least the full viewport height */
        }

        h2{
            text-align: center;
        }

        .statistics-container {
            display: flex;
            justify-content: space-around;
            width: 65%;
            margin-bottom: 50px;
        }

        .stat-box {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 25%;
        }

        .stat-box h3 {
            margin: 10px 0;
            font-size: 20px;
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
            margin:5px;
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

        .record-links {
            text-align: center;
        }

        .record-links a {
            color: #80352F;
            text-decoration: none;
            margin: 0 10px;
        }

        .record-links a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #F1EDE2;
        }
        
        th, td {
            border: 1px solid #80352F;
            padding: 10px;
            text-align: left;
        }
        
        th {
            background-color: #80352F;
            color: white;
        }
        
        td a {
            color: #80352F;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        .collapsible {
            width: 100%;
            text-align: left;
        }

        .collapsible-button {
            background-color: #80352F;
            color: white;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            width: 40%;
            text-align: center;
            margin: 0 auto; /* center the button horizontally */
            display: block; /* ensure the button is treated as a block element */
        }

        .collapsible-content {
            display: none;
            padding: 0 18px;
            justify-content: center; 
            align-items: center; 
        }

        .collapsible-button.active + .collapsible-content {
            display: block;
            margin-top: 10px;
        }

        .collapsible-content .form-container {
        margin: 0 auto; /* Center the form-container */
        }

        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 350px;
        }

        .search-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #80352F;
            color: white;
            cursor: pointer;
            margin-left: 10px;
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
            <a href="manageUsers.php">Manage Users</a>
            <a href="manageAppointments.php">Manage Appointments</a>
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

    <!-- Admin Panel Container -->
    <div class="admin-panel-container">
        <h1>Welcome to Admin Panel</h1>
        <div class="statistics-container">
            <div class="stat-box">
                <h3>Total Appointments (up-to-date)</h3>
                <p><?php echo $total_appointments; ?></p>
            </div>
        </div>

        <div class="record-links">
            <h1>Manage Records</h1>
        </div>

        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search for appointment details...">
            <button onclick="searchTable()">Search</button>
        </div>

        <!-- Manage Appointments Table -->
        <h2>Manage Appointments</h2>
        <table id="appointmentsTable" class="display">
            <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Patients ID</th>
                <th>Name</th>
                <th>Doctor ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Queue Number</th>
                <th>Medical Condition</th>
                <th>Self/Family</th>
                <th>Relationship Type</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($appointments_result && mysqli_num_rows($appointments_result) > 0) {
                while($row = mysqli_fetch_assoc($appointments_result)) {
                    $is_for_self_display = $row['is_for_self'] == 1 ? "Self" : "Family";
                    echo "<tr>
                            <td>{$row['appointment_id']}</td>
                            <td>{$row['patients_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['doctor_id']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['time']}</td>
                            <td>{$row['queue_number']}</td>
                            <td>{$row['medical_condition']}</td>
                            <td>{$is_for_self_display}</td>
                            <td>{$row['relationship_type']}</td>
                            <td>
                                <a href='#' class='edit-link' data-id='{$row['appointment_id']}' data-patients_id='{$row['patients_id']}' data-name='{$row['name']}' data-date='{$row['date']}' data-time='{$row['time']}' data-queue_number='{$row['queue_number']}' data-medical_condition='{$row['medical_condition']}' data-is_for_self='{$row['is_for_self']}' data-relationship_type='{$row['relationship_type']}'>Edit</a> |  
                                <a href='manageAppointments.php?delete_id={$row['appointment_id']}'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='11'>No appointments found</td></tr>";
            }
            ?>
            </tbody>
        </table>

        <!-- Collapsible Add New Appointment Form -->
        <div class="collapsible">
        <button class="collapsible-button">Add New Appointment</button>
            <div class="collapsible-content">
                <div class="form-container">
                    <form action="manageAppointments.php" method="POST">
                        <label for="patients_id">Patients Name:</label>
                        <select id="patients_id" name="patients_id" required>
                        <option value="">Select Patient</option>
                        <?php foreach ($patients as $id => $name): ?>
                            <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
                        <?php endforeach; ?>
                        </select>
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" required>
                        <label for="time">Time:</label>
                        <input type="time" id="time" name="time" required>
                        <label for="queue_number">Queue Number:</label>
                        <input type="number" id="queue_number" name="queue_number" required>
                        <label for="medical_condition">Medical Condition:</label>
                        <input type="text" id="medical_condition" name="medical_condition" required>
                        <label for="is_for_self">Booking for:</label>
                        <div>
                            <input type="radio" id="is_for_self_myself" name="is_for_self" value="1" required>
                            <label for="is_for_self_myself">Myself</label>
                            <input type="radio" id="is_for_self_family" name="is_for_self" value="0" required>
                            <label for="is_for_self_family">Family</label>
                        </div>
                        <div id="family_info" style="display: none;">
                            <label for="relationship_type">Relationship Type:</label>
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
        </div>
    </div>

       <!-- Edit Appointment Form -->
       <div class="form-container" id="editAppointmentForm" style="display:none;">
        <h2>Edit Appointment</h2>
        <form action="manageAppointments.php" method="POST">
            <input type="hidden" id="edit_appointment_id" name="appointment_id">
            <label for="edit_patients_id">Patients ID:</label>
            <select id="edit_patients_id" name="patients_id" required>
            <option value="">Select Patient</option>
            <?php foreach ($patients as $id => $name): ?>
                <option value="<?php echo $id; ?>"><?php echo $name; ?></option>
            <?php endforeach; ?>
            </select>
            <label for="edit_date">Date:</label>
            <input type="date" id="edit_date" name="date" required>
            <label for="edit_time">Time:</label>
            <input type="time" id="edit_time" name="time" required>
            <label for="edit_queue_number">Queue Number:</label>
            <input type="number" id="edit_queue_number" name="queue_number" required>
            <label for="edit_medical_condition">Medical Condition:</label>
            <input type="text" id="edit_medical_condition" name="medical_condition" required>
            <label for="edit_is_for_self">Booking for:</label>
            <div>
                <input type="radio" id="edit_is_for_self_myself" name="is_for_self" value="1" required>
                <label for="edit_is_for_self_myself">Myself</label>
                <input type="radio" id="edit_is_for_self_family" name="is_for_self" value="0" required>
                <label for="edit_is_for_self_family">Family</label>
            </div>
            <div id="edit_family_info" style="display: none;">
                <label for="edit_relationship_type">Relationship Type:</label>
                <select id="edit_relationship_type" name="relationship_type">
                    <option value="spouse">Spouse</option>
                    <option value="child">Child</option>
                    <option value="parent">Parent</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="button-container">
                <button type="submit">Update Appointment</button>
            </div>
        </form>
    </div><br><br>

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#appointmentsTable').DataTable();
        });
        
        //function for collapsible button leading to forms
        document.querySelectorAll('.collapsible-button').forEach(button => { 
        //selects all elements that have the class, loop that iterates over each button with the class
            button.addEventListener('click', () => { //adds a click event listener to each button
                button.classList.toggle('active');
                let content = button.nextElementSibling; //select collapsible content
                if (content.style.display === "block") { //check if display property of the content is set to block
                    content.style.display = "none"; // hide the content
                } else {
                    content.style.display = "block"; // make content visible
                }
            });
        });

            //function for search
            function searchTable() {
            var input, filter, appointmentsTable, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput"); // get the search input element
            filter = input.value.toLowerCase(); // get the search query and convert to lowercase
            appointmentsTable = document.getElementById("appointmentsTable"); // get appointment table by ID

            // Search appointments table
            tr = appointmentsTable.getElementsByTagName("tr"); // get all the tr elements in patient table
            for (i = 1; i < tr.length; i++) {  // start from 1 to skip the header row
                tr[i].style.display = "none"; // hide the row by default
                td = tr[i].getElementsByTagName("td"); // get all td elements in the row
                for (j = 0; j < td.length; j++) {  // loop through all cells in the row
                    if (td[j]) { // if the cell exists
                        txtValue = td[j].textContent || td[j].innerText; // get the text content of the cell
                        //if the cell text contains search query
                        if (txtValue.toLowerCase().indexOf(filter) > -1) { 
                            tr[i].style.display = ""; // show the row
                            break; //stop checking other cells in the row
                        }
                    }
                }
            }
        }

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

        // Display relationship type field if it is family, or else hide field for edit form
        document.querySelectorAll('input[name="is_for_self"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const familyInfo = document.getElementById('edit_family_info');
            if (this.value === '0') {
                familyInfo.style.display = 'block';
            } else {
                familyInfo.style.display = 'none';
            }
        })
        });

        // Edit appointment functionality
        document.querySelectorAll('.edit-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('edit_appointment_id').value = this.dataset.id;
                document.getElementById('edit_patients_id').value = this.dataset.patients_id;
                document.getElementById('edit_date').value = this.dataset.date;
                document.getElementById('edit_time').value = this.dataset.time;
                document.getElementById('edit_queue_number').value = this.dataset.queue_number;
                document.getElementById('edit_medical_condition').value = this.dataset.medical_condition;
                if (this.dataset.is_for_self === '1') { // "1" indicates self
                    document.getElementById('edit_is_for_self_myself').checked = true;
                    document.getElementById('edit_family_info').style.display = 'none';
                } else {
                    document.getElementById('edit_is_for_self_family').checked = true;
                    document.getElementById('edit_family_info').style.display = 'block';
                    document.getElementById('edit_relationship_type').value = this.dataset.relationship_type;
                }
                document.getElementById('editAppointmentForm').style.display = 'block';
            });
        });
    </script>
</body>
</html>
