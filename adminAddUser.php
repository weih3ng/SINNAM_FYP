<?php
session_start();

include 'dbfunctions.php';

// Handle user form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    // Get form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $contactnumber = $_POST['contactnumber'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Insert the new user into the database
    $sql = "INSERT INTO patients (name, age, email, contactnumber, password, dob, gender, username) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'sisissss', $name, $age, $email, $contactnumber, $password, $dob, $gender, $username);

    if (mysqli_stmt_execute($stmt)) {
        // Set success message
        $_SESSION['success_message_user'] = "New user added!";
        header("Location: manageUsers.php");
        exit();
    } else {
        $error_message = "Error: " . mysqli_error($link);
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
    <title>Add New User</title>
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
        <?php
        if (isset($_SESSION['username'])) { 
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

    <!-- Admin Panel Container -->
    <div class="admin-panel-container">
        <h1><i class="fas fa-user-plus"></i> Add New User</h1>
        <div class="form-container">
            <form action="AdminAddUser.php" method="POST">
                <label for="name" class="required-label">Name:</label>
                <input type="name" id="name" name="name" required>
                <label for="age" class="required-label">Age:</label>
                <input type="number" id="age" name="age" required>
                <label for="email" class="required-label">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="contactnumber" class="required-label">Contact Number:</label>
                <input type="number" id="contactnumber" name="contactnumber" required>
                <label for="password" class="required-label">Password:</label>
                <input type="password" id="password" name="password" required>
                <label for="dob" class="required-label">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>
                <div class="radio-group">
                    <label for="gender" class="required-label">Gender:</label>
                    <label><input type="radio" id="male" name="gender" value="male" required> Male</label>
                    <label><input type="radio" id="female" name="gender" value="female" required> Female</label>
                </div>
                <label for="username" class="required-label">Username:</label>
                <input type="text" id="username" name="username" required>
                <div class="button-container">
                    <button type="submit">Add User</button>
                </div>
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
            <span style="margin-right: 10px;">Follow us</span>
            <a href="https://www.facebook.com/profile.php?id=167794019905102&_rdr"><i class="fa-brands fa-facebook"></i></a>
        </div>
    </footer>
</body>
</html>
