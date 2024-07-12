<?php
session_start();

include 'dbfunctions.php';

// Initialize variables for success messages
$success_message_user = "";

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $patients_id = $_GET['delete_id'];

    // Delete the user from the database
    $sql = "DELETE FROM patients WHERE patients_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $patients_id);

    if (mysqli_stmt_execute($stmt)) {
        // Success message
        $_SESSION['success_message_user'] = "User deleted successfully!";
    } else {
        // Error message
        $_SESSION['error_message_user'] = "Error: " . mysqli_error($link);
    }

    // Redirect back to manageUsers.php
    header("Location: manageUsers.php");
    exit();
}

// Handle user form submission (Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contactnumber = $_POST['contactnumber'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];

        // Update existing user
        $patients_id = $_POST['patients_id'];
        $sql = "UPDATE patients SET name = ?, email = ?, contactnumber = ?, dob = ?, gender = ?, username = ? WHERE patients_id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, 'ssisssi', $name, $email, $contactnumber, $dob, $gender, $username, $patients_id);

    if (mysqli_stmt_execute($stmt)) {
        // Set success message
        $success_message_user = isset($_POST['patients_id']) ? "User updated successfully!" : "New user added!";
    } else {
        $success_message_user = "Error: " . mysqli_error($link);
    }

    // Redirect back to manageUsers.php to reflect changes
    header("Location: manageUsers.php");
    exit();  
}

// Fetch patients data
$patients_sql = "SELECT patients_id, name, dob, gender, email, contactnumber, username FROM patients";
$patients_result = mysqli_query($link, $patients_sql);

// Count total active users (patients who have booked an appointment)
$active_patients_sql = "SELECT COUNT(DISTINCT patients_id) as active_patients FROM appointments";
$active_patients_result = mysqli_query($link, $active_patients_sql);
$active_patients_row = mysqli_fetch_assoc($active_patients_result);
$active_patients = $active_patients_row['active_patients'];

// Count total inactive users (patients who have not booked an appointment)
$inactive_patients_sql = "SELECT COUNT(*) as inactive_patients FROM patients WHERE patients_id NOT IN (SELECT DISTINCT patients_id FROM appointments)";
$inactive_patients_result = mysqli_query($link, $inactive_patients_sql);
$inactive_patients_row = mysqli_fetch_assoc($inactive_patients_result);
$inactive_patients = $inactive_patients_row['inactive_patients'];
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
    <title>Manage Users</title>
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

        .statistics-container {
            display: flex;
            justify-content: space-around;
            width: 55%;
            margin-bottom: 50px;
        }

        .stat-box {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 25%;
            transition: transform 0.3s ease; /* Added transition for smooth scaling */
        }

        .stat-box:hover {
            transform: scale(1.15); /* Enlarge the container slightly on hover */
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

        .form-container label.required-label::before {
            content: " *";
            color: red;
            margin-left: 5px;
        }

        .form-container input {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 30px;
            border: 1px solid #ccc;
        }

        .form-container input[type="text"],
        .form-container input[type="name"],
        .form-container input[type="number"],
        .form-container input[type="email"],
        .form-container input[type="password"],
        .form-container input[type="date"] {
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
            margin-top: 10px;
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

        .dataTables_length {
            margin-bottom: 10px; 
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
        <h1>Welcome to Admin Panel</h1><br>
        <div class="statistics-container">
            <div class="stat-box" id="activeUsers" data-tippy-content="Booked an appointment">
                <h3><i class="fas fa-users"></i> Active Users</h3>
                <p><?php echo $active_patients; ?></p>
            </div>
            <div class="stat-box" id="inactiveUsers" data-tippy-content="Yet to book an appointment / account not used">
                <h3><i class="fas fa-user-times"></i> Inactive Users</h3>
                <p><?php echo $inactive_patients; ?></p>
            </div>
        </div>

        <div class="record-links">
            <h1>Search Records</h1>
        </div>

        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search for patient details...">
            <button onclick="searchTable()">Search</button>
        </div>

    <!-- Manage Users Table -->
    <h2>Manage Users</h2>
    <div class="button-container">
        <a href="adminAddUser.php"><button>Add User</button></a>
    </div>
        <table id="patientsTable" class="display">
            <thead>
            <tr>
                <th>Patients ID</th>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if ($patients_result && mysqli_num_rows($patients_result) > 0) {
                while($row = mysqli_fetch_assoc($patients_result)) {
                    echo "<tr>
                            <td>{$row['patients_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['dob']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['contactnumber']}</td>
                            <td>{$row['username']}</td>
                            <td>
                                <a href='#' class='edit-link' data-id='{$row['patients_id']}' data-name='{$row['name']}' data-email='{$row['email']}' data-dob='{$row['dob']}' data-gender='{$row['gender']}' data-contactnumber='{$row['contactnumber']}' data-username='{$row['username']}'>Edit</a> | 
                                <a href='#' class='delete-link' data-id='{$row['patients_id']}'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No users found</td></tr>";
            }
            ?>
            </tbody>
        </table>

    <!--Edit User Form -->
        <div class="form-container" id="editUserForm" style="display:none;">
        <h2>Edit User</h2>
            <form action="manageUsers.php" method="POST">
                <input type="hidden" id="edit_patients_id" name="patients_id">
                <label for="edit_name" class="required-label">Name:</label>
                <input type="text" id="edit_name" name="name" required>
                <label for="edit_email" class="required-label">Email:</label>
                <input type="email" id="edit_email" name="email" required>
                <label for="edit_contactnumber" class="required-label">Contact Number:</label>
                <input type="text" id="edit_contactnumber" name="contactnumber" required>
                <label for="edit_dob" class="required-label">Date of Birth:</label>
                <input type="date" id="edit_dob" name="dob" required>
                <div class="radio-group">
                    <label for="edit_gender" class="required-label">Gender:</label>
                    <label><input type="radio" id="edit_male" name="gender" value="male" required> Male</label>
                    <label><input type="radio" id="edit_female" name="gender" value="female" required> Female</label>
                </div>
                <label for="edit_username" class="required-label">Username:</label>
                <input type="text" id="edit_username" name="username" required>
                <div class="button-container">
                    <button type="submit">Update User</button>
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
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

    <script>
        $(document).ready(function() {
            $('#patientsTable').DataTable({
                searching: false // Disable the search bar
            });
        });

        // Initialize Tippy.js tooltips
            tippy('#activeUsers', {
            content: 'Booked appointment'
        });

            tippy('#inactiveUsers', {
            content: 'Yet to book an appointment/not used account'
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
        var input, filter, patientsTable, tr, td, i, j, txtValue;
        input = document.getElementById("searchInput"); // get the search input element
        filter = input.value.toLowerCase().split(" "); // get the search query and split it into an array of terms
        patientsTable = document.getElementById("patientsTable"); // get patients table by ID

        // Search patients table
        tr = patientsTable.getElementsByTagName("tr"); // get all the tr elements in patient table
        for (i = 1; i < tr.length; i++) {  // start from 1 to skip the header row
            tr[i].style.display = "none"; // hide the row by default
            var matches = 0; // counter for matching criteria

            // get all td elements in the row
            td = tr[i].getElementsByTagName("td"); 
            for (j = 0; j < td.length; j++) {  // loop through all cells in the row
                if (td[j]) { // if the cell exists
                    txtValue = td[j].textContent || td[j].innerText; // get the text content of the cell
                    // check if the cell text contains any of the search terms
                    for (var k = 0; k < filter.length; k++) {
                        if (txtValue.toLowerCase().indexOf(filter[k]) > -1) {
                            matches++;
                            break; // move to the next cell if a match is found
                        }
                    }
                }
            }
            // show the row if it matches both search criteria
            if (matches >= filter.length) {
                tr[i].style.display = "";
            }
        }
    }

        // Edit user functionality
        document.querySelectorAll('.edit-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('edit_patients_id').value = this.dataset.id;
                document.getElementById('edit_name').value = this.dataset.name;
                document.getElementById('edit_email').value = this.dataset.email;
                document.getElementById('edit_contactnumber').value = this.dataset.contactnumber;
                document.getElementById('edit_dob').value = this.dataset.dob;
                if (this.dataset.gender === 'male') {
                    document.getElementById('edit_male').checked = true;
                } else {
                    document.getElementById('edit_female').checked = true;
                }
                document.getElementById('edit_username').value = this.dataset.username;
                document.getElementById('editUserForm').style.display = 'block';
            });
        });

        // Set the maximum date for the date of birth field to today's date
        document.addEventListener('DOMContentLoaded', function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('edit_dob').setAttribute('max', today);
        });

        // Delete user confirmation
        document.querySelectorAll('.delete-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var deleteUserId = this.dataset.id;
                var confirmation = confirm('Are you sure you want to delete this user?');
                if (confirmation) {
                    window.location.href = 'manageUsers.php?delete_id=' + deleteUserId;
                }
            });
        });
    </script>
</body>
</html>
