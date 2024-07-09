<?php 
session_start(); // Start the session

include 'dbfunctions.php';

// Check if the user is logged in (joc)
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$patients_id = $_SESSION['patients_id']; // Retrieve patient ID from session (joc)

// Retrieve appointments for the logged-in patient (joc)
$query = "SELECT a.appointment_id, a.date, a.time, a.is_for_self, a.relationship_type, a.medical_condition, p.name, a.family_name
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

$current_date = date('Y-m-d');
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
            min-height: calc(130vh - 120px);
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
            text-decoration: none;
        }

        .btn-edit:hover {
            color: #3E8E41; 
        }

        .btn-delete {
            color: #f44336; 
            text-decoration: none;
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



        /* Color Coding for table rows (joc)*/
        .self-appointment { 
            background-color: #edede9; 
        }  

        .family-appointment { 
            background-color: #d6ccc2; 
        } 

        .table-header {
            display: flex;
            justify-content: space-between; /* Push the dropdown towards to the right */
            align-items: center;
            margin-bottom: 10px;
        }

        select#appointmentFilter {
            padding: 8px;
            margin-right: 0;
            width: 200px;
            border-radius: 5px;
            border: 2px solid #80352F;
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

            <div class="table-header">
                <div></div> <!-- Placeholder for spacing -->
                <select id="appointmentFilter" name="appointmentFilter">
                    <option value="all">All Appointments</option>
                    <option value="self">Only Self Appointments</option>
                    <option value="family">Only Family Appointments</option>
                </select>
            </div>


            <table>
                <thead>
                    <tr>
                        <th>Queue Number</th>
                        <th>Booking Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Medical Condition</th>
                        <th>Self/Family</th>
                        <th>Relationship Type</th>
                        <th>Family Name</th> 
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment) : ?>
                        <tr class="<?= $appointment['is_for_self'] ? 'self-appointment' : 'family-appointment'; ?>">
                            <td><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['name']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['medical_condition']); ?></td>
                            <td><?php echo $appointment['is_for_self'] ? 'Self' : 'Family'; ?></td>
                            <td><?php echo htmlspecialchars($appointment['relationship_type']); ?></td>
                            <td><?php echo $appointment['is_for_self'] ? '' : htmlspecialchars($appointment['family_name']); ?></td> <!-- Display family member's name if not a self appointment -->
                            <td class="action-buttons">
                                <?php if ($appointment['date'] >= $current_date) : ?>
                                    <a href="editAppointment.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="deleteAppointment.php?appointment_id=<?php echo $appointment['appointment_id']; ?>" class="btn btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
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
    
    
    <script>

        // Filter appointments based on 'Self' or 'Family' (joc)
        document.getElementById('appointmentFilter').addEventListener('change', function() {
            const filterValue = this.value;
            const rows = document.querySelectorAll('table tbody tr');

            // Reset the display state for all rows
            rows.forEach(row => {
                row.style.display = '';  // Reset display to default for all rows
            });

            // Now apply the new filter
            rows.forEach(row => {
                const appointmentFor = row.cells[5].textContent.trim();  // Ensure to trim any whitespace

                console.log(`Filtering for: ${filterValue}, Current row: ${appointmentFor}`);  // Debug output

                if (filterValue === 'self' && appointmentFor !== 'Self') {
                    row.style.display = 'none';
                } else if (filterValue === 'family' && appointmentFor !== 'Family') {
                    row.style.display = 'none';
                }
            });
        });






    </script>

</body>
</html>
