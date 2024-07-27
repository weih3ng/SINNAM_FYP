<?php
session_start();

include 'dbfunctions.php';

// Initialize variables for success messages
$success_message_user = "";

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $patients_id = $_GET['delete_id'];

    // Delete the related appointments first
    $delete_appointments_sql = "DELETE FROM appointments WHERE patients_id = ?";
    $stmt = mysqli_prepare($link, $delete_appointments_sql);
    mysqli_stmt_bind_param($stmt, 'i', $patients_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Delete the related testimonials next
    $delete_testimonials_sql = "DELETE FROM testimonials WHERE patients_id = ?";
    $stmt = mysqli_prepare($link, $delete_testimonials_sql);
    mysqli_stmt_bind_param($stmt, 'i', $patients_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

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
    header("Location: adminManageUsers.php");
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
    $patients_id = $_POST['patients_id'];

        // Check for duplicate username
        $duplicate_check_sql = "SELECT COUNT(*) as count FROM patients WHERE username = ? AND patients_id != ?";
        $stmt = mysqli_prepare($link, $duplicate_check_sql);
        mysqli_stmt_bind_param($stmt, 'si', $username, $patients_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $count);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    
        if ($count > 0) {
            // Set JavaScript alert for duplicate username
            echo "<script>
                alert('Error: The same username has been used. Please enter a different username.');
                window.location.href='adminManageUsers.php';
                </script>";
            exit();
        } else {
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
    header("Location: adminManageUsers.php");
    exit();  
    }   
}

// Fetch patients data
$patients_sql = "SELECT patients_id, name, dob, gender, email, contactnumber, username FROM patients";
$patients_result = mysqli_query($link, $patients_sql);

// Fetch active users data
$active_users_sql = "SELECT DISTINCT p.patients_id, p.name FROM patients p INNER JOIN appointments a ON p.patients_id = a.patients_id";
$active_users_result = mysqli_query($link, $active_users_sql);

// Fetch inactive users data
$inactive_users_sql = "SELECT patients_id, name FROM patients WHERE patients_id NOT IN (SELECT DISTINCT patients_id FROM appointments)";
$inactive_users_result = mysqli_query($link, $inactive_users_sql);

// Count total active users
$active_patients = mysqli_num_rows($active_users_result);

// Count total inactive users
$inactive_patients = mysqli_num_rows($inactive_users_result);
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
            width: 38%;
            transition: transform 0.3s ease; /* Added transition for smooth scaling */
        }

        .stat-box:hover {
            transform: scale(1.10); /* Enlarge the container slightly on hover */
        }

        .stat-box h3 {
            margin: 10px 0;
            font-size: 20px;
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

        .form-container input {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 30px;
            border: 1px solid black;
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
            border: 1px solid black;
            border-radius: 20px;
            background-color: white;

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

        .collapsible {
            background-color: #80352F;
            color: white;
            cursor: pointer;
            padding: 10px;
            width: 50%;
            border: none;
            text-align: center;
            outline: none;
            font-size: 15px;
            border-radius: 30px;
            margin-top: 10px;
            font-weight: bold;
        }

        .active, .collapsible:hover {
            background-color: #6b2c27;
        }

        .content {
            padding: 0 18px;
            display: none;
            overflow: hidden;
            background-color: #f9f9f9;
            margin-top: 10px;
            border-radius: 5px;
        }

        .dataTables_filter {
            margin-bottom: 10px; 
            margin-top: 5px; 
        }

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
            <a href="adminManageUsers.php" class="current">Manage Users</a>
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
        <h1>Welcome to Admin Panel</h1><br>
        <div class="statistics-container">
            <div class="stat-box" id="activeUsers" data-tippy-content="Booked an appointment">
                <h3><i class="fas fa-users" style="color:#4CAF50"></i> Active Users</h3>
                <p><?php echo $active_patients; ?></p>
                <button class="collapsible" id="showActiveUsersBtn">Show Users</button>
                <div class="content" id="activeUsersContent">
                <table id="activeAccs" class="display">
                <thead>
                    <tr>
                        <th>Users</th>
                    </tr>
                </thead>
                <tbody>
                        <?php
                            if ($active_users_result && mysqli_num_rows($active_users_result) > 0) {
                                while ($row = mysqli_fetch_assoc($active_users_result)) {
                                    echo "<tr><td>{$row['name']}</td></tr>";
                                }
                            } else {
                                echo "<tr><td>No active users found</td></tr>";
                            }
                            ?>
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="stat-box" id="inactiveUsers" data-tippy-content="Have not booked an appointment / account not in use">
                <h3><i class="fas fa-user-times" style="color: #f44336;"></i> Inactive Users</h3>
                <p><?php echo $inactive_patients; ?></p>
                <button class="collapsible" id="showInactiveUsersBtn">Show Users</button>
                <div class="content" id="inactiveUsersContent">
                <table id="inactiveAccs" class="display">
                <thead>
                    <tr>
                        <th>Users</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if ($inactive_users_result && mysqli_num_rows($inactive_users_result) > 0) {
                            while ($row = mysqli_fetch_assoc($inactive_users_result)) {
                                echo "<tr><td>{$row['name']}</td></tr>";
                            }
                        } else {
                            echo "<tr><td>No inactive users found</td></tr>";
                        }
                        ?>
                    </tbody>
                    </table>
                </div>
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
                                <a href='#' class='edit-link' data-id='{$row['patients_id']}' data-name='{$row['name']}' data-email='{$row['email']}' data-dob='{$row['dob']}' data-gender='{$row['gender']}' data-contactnumber='{$row['contactnumber']}' data-username='{$row['username']}'><i class='fas fa-edit' style='color:#4CAF50;'></i></a> | 
                                <a href='#' class='delete-link' data-id='{$row['patients_id']}'><i class='fas fa-trash' style='color: #f44336;'></i></a>
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
            <form action="adminManageUsers.php" method="POST">
                <input type="hidden" id="edit_patients_id" name="patients_id">
                <label for="edit_name" >Name:</label>
                <input type="text" id="edit_name" name="name" required>
                <label for="edit_email">Email:</label>
                <input type="email" id="edit_email" name="email" required>
                <label for="edit_contactnumber">Contact Number:</label>
                <input type="text" id="edit_contactnumber" name="contactnumber" maxlength="8" pattern="\d{8}" required>
                <label for="edit_dob">Date of Birth:</label>
                <input type="date" id="edit_dob" name="dob" required>
                <div class="radio-group">
                    <label for="edit_gender">Gender:</label>
                    <label><input type="radio" id="edit_male" name="gender" value="male" required> Male</label>
                    <label><input type="radio" id="edit_female" name="gender" value="female" required> Female</label>
                </div>
                <label for="edit_username">Username:</label>
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

        $(document).ready(function() {
            $('#activeAccs').DataTable({
            });
        });

        $(document).ready(function() {
            $('#inactiveAccs').DataTable({
            });
        });

        // Initialize Tippy.js tooltips
            tippy('#activeUsers', {
            content: 'Booked appointment'
        });

            tippy('#inactiveUsers', {
            content: 'Yet to book an appointment/not used account'
        });

        // Function for search
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue, allMatched;
            input = document.getElementById("searchInput"); // Get the search input element
            filter = input.value.toLowerCase(); // Get the search query and convert to lower case
            table = document.getElementById("patientsTable"); // Get patients table by ID
            tr = table.getElementsByTagName("tr"); // Get all the tr elements in the patients table

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
                tr[i].style.display = "none"; // Hide the row by default
                allMatched = false; // Flag to check if any cell matches the search query

                // Get all td elements in the row
                td = tr[i].getElementsByTagName("td");
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) { // If the cell exists
                        txtValue = td[j].textContent || td[j].innerText; // Get the text content of the cell
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            allMatched = true; // Set flag to true if a match is found
                            break; // Exit the loop if a match is found
                        }
                    }
                }

                // Show the row if any cell matches the search query
                if (allMatched) {
                    tr[i].style.display = "";
                }
            }
        }

        // Attach the search function to the search button
        document.querySelector('.search-container button').addEventListener('click', searchTable);

        // Add event listener to handle real-time search and Enter key press
        document.getElementById("searchInput").addEventListener("input", searchTable);
        document.getElementById("searchInput").addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                searchTable();
            }
        });

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
                    window.location.href = 'adminManageUsers.php?delete_id=' + deleteUserId;
                }
            });
        });

        
        document.addEventListener('DOMContentLoaded', function() {
            // Function for collapsible button leading to forms
            document.querySelectorAll('.collapsible').forEach(button => {
                button.addEventListener('click', () => { // Add a click event listener to each 'collapsible' button
                    button.classList.toggle('active'); // Toggle the 'active' class on the clicked button

                    // Get the next sibling element of the button, which is the collapsible content
                    let content = button.nextElementSibling;

                    // Toggle the display property of the content between 'block' and 'none'
                    if (content.style.display === "block") {
                        content.style.display = "none"; //if the content is current displayed, hide it.
                    } else {
                        content.style.display = "block"; //if content is hidden, display it.
                    }
                });
            });
        });
    </script>
</body>
</html>
