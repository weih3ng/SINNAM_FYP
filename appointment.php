<?php 
session_start(); // Start the session

include 'dbfunctions.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['date']) && isset($_POST['timeslot'])) {
        $date = $_POST['date'];
        $time = $_POST['timeslot'];
        $medical_condition = $_POST['medical_conditions']; // Added medical condition (joc)
        $patients_id = $_SESSION['patients_id'];
        $doctor_id = 1; // Assuming a fixed doctor ID for now
        $queue_number = 1; // Function to get the next queue number for now

        // Ensure date format is YYYY-MM-DD
        $formatted_date = date('Y-m-d', strtotime($date));

        // added two columns (joc)
        $is_for_self = ($_POST['booking_for'] == 'self') ? 1 : 0;
        $relationship_type = ($is_for_self == 0 && isset($_POST['relationship_type'])) ? $_POST['relationship_type'] : NULL;

        $sql = "INSERT INTO appointments (patients_id, doctor_id, date, time, queue_number, is_for_self, relationship_type, medical_condition) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "iissiiss", $patients_id, $doctor_id, $formatted_date, $time, $queue_number, $is_for_self, $relationship_type, $medical_condition);
            if (mysqli_stmt_execute($stmt)) {
                $newly_created_appointment_id = mysqli_insert_id($link);  // This captures the last inserted ID (joc)
                $_SESSION['appointment_id'] = $newly_created_appointment_id;  // Store it in session to use later (joc)

                mysqli_stmt_close($stmt);
                mysqli_close($link);
                header("Location: appointmentConfirm.php");
                exit(); // Ensure script stops executing after redirection
            } else {
                echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
            }
        } else {
            echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
        }
    } else {
        echo "ERROR: Date, timeslot, and medical condition are required.";
    }
}

mysqli_close($link);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Doctor Appointment</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <style>
        /* Adjusting Font Weight */
        footer {
            font-weight: normal; /* Ensure normal font weight */
        }

        .container {
            display: flex; /* Makes the container a flex container so that items are well-aligned */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            padding: 50px 20px;
            height: calc(100vh - 100px); /* Adjust height to fit within the viewport */
        }

        #datepicker {
            margin: 20px 0;
        }

        #timeslot {
            padding: 18px;
            font-size: 18px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: 150px;
        }

        /* Add custom styles for the radio button (joc) */
        #relationship {
            padding: 8px;
            font-size: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: 100px;
        }

        /* Add custom styles for the textarea (joc) */
        #medical-conditions {
            padding: 0px 50px;
            font-size: 18px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .calendar {
            width: 100%;
            max-width: 500px;
        }

        .content-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
        }

        .timeslot-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
            font-size: 20px; /* Increase font-size for better readability */
        }

        .btn-book {
            background-color: #80352F;
            color: white;
            border: none;
            padding: 10px 50px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 30px;
            margin-top: 20px; /* Adjust margin-top to create space */
            transition: background-color 0.1s ease;
        }

        .btn-book:hover {
            background-color: #6b2c27;
        }

        /* Disabled state for the button (joc) */
        .btn-book:disabled {
            background-color: #ccc; 
            color: #666; 
            cursor: not-allowed; 
            box-shadow: 5px 5px 15px grey;
        }

        h1 {
            text-align: center;
            margin: 20px 0 80px 0;
        }

        /* Custom styles to make the calendar larger */
        .ui-datepicker {
            font-size: 1.5em; 
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


        <?php if (isset($_SESSION['username'])) { ?>
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

    <div class="container">
        <?php if (isset($_SESSION['loggedin']) || isset($_SESSION['loggedin']) == true) { ?> <!-- Check if user is logged in (joc) -->
        
            <h1>Schedule Doctor Appointment</h1>
            <div class="content-wrapper">

                <div id="calendar-container" class="calendar"></div>

                <form action="appointment.php" method="post" class="timeslot-container">
                    <label for="timeslot"><b>Select time slot:<b></label>
                    <input type="hidden" name="date" id="selected-date" />
                    <select id="timeslot" name="timeslot">
                        <option value="10:30 AM">10:30 AM</option>
                        <option value="11:00 AM">11:00 AM</option>
                        <option value="12:00 PM">12:00 PM</option>
                        <option value="1:00 PM">1:00 PM</option>
                        <option value="2:00 PM">2:00 PM</option>
                        <option value="3:00 PM">3:00 PM</option>
                        <option value="4:00 PM">4:00 PM</option>
                        <option value="5:00 PM">5:00 PM</option>
                    </select>

                    <br><br><br> <!-- Add booking for myself/family member (joc) -->
                    <label><b>Booking for:</b></label> 
                    <br><br>
                    <div>
                        <input type="radio" id="for_self" name="booking_for" value="self" checked>
                        <label for="for_self" style= "font-size: 17px;">Myself</label>
                        <input type="radio" id="for_family" name="booking_for" value="family">
                        <label for="for_family" style= "font-size: 17px;">Family Member</label>
                    </div>
                    <br>
                    <div id="family_info" style="display: none;">
                        <label for="relationship" style= "font-size: 17px;"><b>Relationship:</b></label>
                        <select id="relationship" name="relationship_type">
                            <option value="spouse">Spouse</option>
                            <option value="child">Child</option>
                            <option value="parent">Parent</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <br><br> <!-- Add medical condition (joc) -->
                    <label for="medical-conditions"><b>Reason for consult (Medical Condition): <b></label>
                    <br><br>
                    <textarea id="medical-conditions" name="medical_conditions" rows="4" style="width:100%;"></textarea>

                    <button class="btn-book">Book</button>
                </form>
            </div>
        <?php }
        else { ?> <!-- If user is not logged in, display message (joc) -->
            <h1>Schedule Doctor Appointment</h1>
            <p style="color: red; margin-bottom: 70px; margin-top: -50px">Please login to book appointment!</p>
            <div class="content-wrapper">

                <div id="calendar-container" class="calendar"  style="pointer-events: none; opacity: 0.7;"></div>

                <form action="appointmentConfirm.php" method="post" class="timeslot-container">
                    <label for="timeslot"><b>Select time slot:<b></label>
                    <select id="timeslot" disabled>
                        <option value="10:30 AM">10:30 AM</option>
                        <option value="11:00 AM">11:00 AM</option>
                        <option value="12:00 PM">12:00 PM</option>
                        <option value="1:00 PM">1:00 PM</option>
                        <option value="2:00 PM">2:00 PM</option>
                        <option value="3:00 PM">3:00 PM</option>
                        <option value="4:00 PM">4:00 PM</option>
                        <option value="5:00 PM">5:00 PM</option>
                    </select>

                    <br><br><br> <!-- Add booking for myself/family member (joc) -->
                    <label><b>Booking for:</b></label>
                    <br><br>
                    <div>
                        <input type="radio" id="for_self" name="booking_for" value="self" disabled>
                        <label for="for_self">Myself</label>
                        <input type="radio" id="for_family" name="booking_for" value="family" disabled>
                        <label for="for_family">Family Member</label>
                    </div>
                    <br>
                    <div id="family_info" style="display: none;">
                        <label for="relationship"><b>Relationship:</b></label>
                        <select id="relationship" name="relationship_type">
                            <option value="spouse">Spouse</option>
                            <option value="child">Child</option>
                            <option value="parent">Parent</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <br><br><br> <!-- Add medical condition (joc) -->
                    <label for="medical-conditions"><b>Reason for consult (Medical Condition): <b></label>
                    <br><br>
                    <textarea id="medical-conditions" name="medical_conditions" rows="4" style="width:100%;" disabled></textarea>

                    <button class="btn-book" disabled>Book</button>
                </form>
                </div>
        <?php } ?>
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
        $(function() {
            $("#calendar-container").datepicker({
                inline: true,
                minDate: 0, // Restrict to today and future dates
                dateFormat: "yy-mm-dd", // Set the date format to YYYY-MM-DD
                beforeShowDay: function(date) {
                    var day = date.getDay(); // Get the day of the week (0 - Sunday, 1 - Monday, ...)
                    
                    // Define valid time slots for each day
                    var validTimeSlots = [];
                    switch (day) {
                        case 0: // Sunday
                            break; // No appointments on Sundays
                        case 1: // Monday
                            break; // No appointments on Mondays
                        case 2: // Tuesday
                        case 3: // Wednesday
                        case 4: // Thursday
                        case 5: // Friday
                            validTimeSlots = ["11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM", "12:00 PM", "12:15 PM", "12:30 PM", "12:45 PM", "1:00 PM", "1:15 PM", "1:30 PM", "1:45 PM", "2:00 PM", "2:15 PM", "2:30 PM", "2:45 PM", "3:00 PM", "3:15 PM", "3:30 PM", "3:45 PM", "4:00 PM", "4:15 PM", "4:30 PM"];
                            break;
                        case 6: // Saturday
                            validTimeSlots = ["10:30 AM", "10:45 AM", "11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM", "12:00 PM", "12:15 PM", "12:30 PM", "12:45 PM", "1:00 PM", "1:15 PM", "1:30 PM", "1:45 PM", "2:00 PM"];
                            break;
                    }
                    
                    // Disable the day if there are no valid time slots
                    if (validTimeSlots.length === 0) {
                        return [false, "", "No appointments"];
                    }

                    return [true, "", ""]; // Enable the day
                },
                onSelect: function(dateText) {
                    $("#selected-date").val(dateText); // Set the formatted date
                }
            });
        });

        // Add event listener to show family info when booking for family (joc)
        document.querySelectorAll('input[name="booking_for"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const familyInfo = document.getElementById('family_info');
                if (this.value === 'family') {
                    familyInfo.style.display = 'block';
                } else {
                    familyInfo.style.display = 'none';
                }
            });
        });


    </script>
</body>
</html>
