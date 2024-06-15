<?php 
session_start(); // Start the session

include 'dbfunctions.php';
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
            min-height: calc(100vh - 120px);
            padding: 20px;
            box-sizing: border-box;
        }

        .content-box {
            background-color: #DECFBC;
            border-radius: 80px;
            padding: 80px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
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
        }

        .btn-edit:hover {
            color: #3E8E41; 
        }

        .btn-delete {
            color: #f44336; 
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
            <a href="contact.php">Contact Us</a>
        </div>

        <!-- Sign Up & Login Button -->
        <a class="nav-custom" href="signUp.php">
            <i class="fa-solid fa-user"></i> Sign Up
        </a>

        <?php

        if (isset($_SESSION['username'])) { ?>


        <a class="nav-custom" href="logout.php">
            <i class="fa-solid fa-right-to-bracket"></i> Logout
        </a>  
        <?php }else { ?>
            <a class="nav-custom" href="login.php">
            <i class="fa-solid fa-right-to-bracket"></i> Login
        </a>  

        
        <?php } ?>

    </div>


    <!-- View Appointment Container -->
    <div class="view-appointment-container">
        <div class="content-box">
            <h1>View Appointment</h1>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
                <!-- Example Data -->
                <tr>
                    <td>1</td>
                    <td>John Tan</td>
                    <td>23/04/2023</td>
                    <td>1300</td>
                    <td class="action-buttons">
                        <a href="editAppointment.php" class="btn btn-edit">Edit</a>
                        <a href="deleteAppointment.php" class="btn btn-delete">Delete</a>
                    </td>
                    </td>
                </tr>
                <!-- Repeat for each appointment -->
                <tr>
                    <td>2</td>
                    <td>Kelvin Tan</td>
                    <td>26/04/2023</td>
                    <td>1100</td>
                    <td class="action-buttons">
                        <a href="editAppointment.php" class="btn btn-edit">Edit</a>
                        <a href="deleteAppointment.php" class="btn btn-delete">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Josh Lee</td>
                    <td>26/04/2023</td>
                    <td>1115</td>
                    <td class="action-buttons">
                        <a href="editAppointment.php" class="btn btn-edit">Edit</a>
                        <a href="deleteAppointment.php" class="btn btn-delete">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Mary Leong</td>
                    <td>15/05/2023</td>
                    <td>1200</td>
                    <td class="action-buttons">
                        <a href="editAppointment.php" class="btn btn-edit">Edit</a>
                        <a href="deleteAppointment.php" class="btn btn-delete">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>John Tan</td>
                    <td>23/04/2023</td>
                    <td>1300</td>
                    <td class="action-buttons">
                        <a href="editAppointment.php" class="btn btn-edit">Edit</a>
                        <a href="deleteAppointment.php" class="btn btn-delete">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>Tan Wei Liang</td>
                    <td>19/05/2023</td>
                    <td>1500</td>
                    <td class="action-buttons">
                        <a href="editAppointment.php" class="btn btn-edit">Edit</a>
                        <a href="deleteAppointment.php" class="btn btn-delete">Delete</a>
                    </td>
                </tr>
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

    </body>
</html>