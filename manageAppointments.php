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

// Handle appointment form submission (Edit)
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

        if (isset($_POST['appointment_id'])) {
            // Update existing appointment
            $appointment_id = $_POST['appointment_id'];
            $sql = "UPDATE appointments SET patients_id = ?, doctor_id = ?, date = ?, time = ?, is_for_self = ?, relationship_type = ?, medical_condition = ? WHERE appointment_id = ?";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, 'iississi', $patients_id, $doctor_id, $date, $time, $is_for_self, $relationship_type, $medical_condition, $appointment_id);

        if (mysqli_stmt_execute($stmt)) {
            // Set success message
            $success_message_appointment = isset($_POST['appointment_id']) ? "Appointment updated successfully!" : "New appointment added!";
        } else {
            $success_message_appointment = "Error: " . mysqli_error($link);
        }
    
        // Redirect back to manageAppointments.php
        header("Location: manageAppointments.php");
        exit();
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
$appointments_sql = "SELECT a.appointment_id, a.patients_id, a.doctor_id, a.date, a.time, a.is_for_self,
        a.relationship_type, a.medical_condition, p.name
        FROM appointments AS a
        INNER JOIN patients AS p ON a.patients_id = p.patients_id";
$appointments_result = mysqli_query($link, $appointments_sql);
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
            margin-bottom: 30px;
        }

        .stat-box {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 40%;
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
        
        .button-container button {
            text-align: center;
            margin-bottom: 20px;
            color: white;
            background-color: #80352F;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            padding: 10px 50px;
            font-size: 16px;
            width: 180px;
        }

        .button-container button:hover {
            background-color: #6b2c27;
        }

        td a.edit-link {
            color: green;
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
        <h1>Welcome to Admin Panel</h1><br><br>

        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search for appointment details...">
            <button onclick="searchTable()">Search</button>
        </div>

        <!-- Manage Appointments Table -->
        <h2>Manage Appointments</h2>
        <div class="button-container">
            <a href="adminAddAppointment.php"><button>Add Appointment</button></a>
        </div>
        <table id="appointmentsTable" class="display">
            <thead>
            <tr>
                <th>Queue Number</th>
                <th>Booking Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Medical Condition</th>
                <th>Self/Family</th>
                <th>Relationship</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Fetch current date
            $current_date = date("Y-m-d");

            if ($appointments_result && mysqli_num_rows($appointments_result) > 0) {
                while($row = mysqli_fetch_assoc($appointments_result)) {
                    $is_for_self_display = $row['is_for_self'] == 1 ? "Self" : "Family";
                    echo "<tr>
                            <td>{$row['appointment_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['time']}</td>
                            <td>{$row['medical_condition']}</td>
                            <td>{$is_for_self_display}</td>
                            <td>{$row['relationship_type']}</td>
                            <td>";
                            // Check if appointment date is in the past
                            if ($row['date'] >= $current_date) {
                                echo"<a href='#' class='edit-link' data-id='{$row['appointment_id']}' data-patients_id='{$row['patients_id']}' data-name='{$row['name']}' data-date='{$row['date']}' data-time='{$row['time']}' data-medical_condition='{$row['medical_condition']}' data-is_for_self='{$row['is_for_self']}' data-relationship_type='{$row['relationship_type']}'>Edit</a> |  
                                <a href='manageAppointments.php?delete_id={$row['appointment_id']}'>Delete</a>";
                            } else {
                                echo "";
                            }
                            echo "</td>
                                  </tr>";
                            }
                        } else {
                echo "<tr><td colspan='8'>No appointments found</td></tr>";
            }
            ?>
            </tbody>
        </table>

       <!-- Edit Appointment Form -->
       <div class="form-container" id="editAppointmentForm" style="display:none;">
        <h2>Edit Appointment</h2>
        <form action="manageAppointments.php" method="POST">
            <input type="hidden" id="edit_appointment_id" name="appointment_id">
            <label for="edit_patients_id">Booking Name:</label>
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
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

    <script>
        $(document).ready(function() {
            $('#appointmentsTable').DataTable();
        });

        function searchTable() {
        var input, filters, table, tr, td, i, j, txtValue;
        input = document.getElementById("searchInput"); // get the search input element
        filters = input.value.toLowerCase().split(" "); // get the search query and split it into an array of terms
        table = document.getElementById("appointmentsTable"); // get the table by ID

        // Search appointments table
        tr = table.getElementsByTagName("tr"); // get all the tr elements in the table
        for (i = 1; i < tr.length; i++) {  // start from 1 to skip the header row
            tr[i].style.display = "none"; // hide the row by default
            var matches = 0; // counter for matching criteria

            // get all td elements in the row
            td = tr[i].getElementsByTagName("td");
            for (j = 0; j < td.length; j++) {  // loop through all cells in the row
                if (td[j]) { // if the cell exists
                    txtValue = td[j].textContent || td[j].innerText; // get the text content of the cell
                    // check if the cell text contains any of the search terms
                    for (var k = 0; k < filters.length; k++) {
                        if (txtValue.toLowerCase().indexOf(filters[k]) > -1) {
                            matches++;
                            break; // move to the next cell if a match is found
                        }
                    }
                }
            }
            // show the row if it matches all search criteria
            if (matches >= filters.length) {
                tr[i].style.display = "";
            }
        }
    }

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

        // Disable Sundays and Mondays in the date picker
        document.getElementById('edit_date').addEventListener('input', function(e) {
            var day = new Date(this.value).getUTCDay();
            if (day === 0 || day === 1) {
                alert('Booking on Sunday and Monday is not allowed. Please select another date.');
                this.value = '';
            }
        });
    </script>
</body>
</html>
