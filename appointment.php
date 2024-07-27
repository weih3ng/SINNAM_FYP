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
        $admin_id = 1; // Assuming a fixed admin ID for now

        // Ensure date format is YYYY-MM-DD
        $formatted_date = date('Y-m-d', strtotime($date));

        // Check if 'family_member_name' included or not (joc)
        $is_for_self = ($_POST['booking_for'] == 'self') ? 1 : 0;
        $relationship_type = ($is_for_self == 0 && isset($_POST['relationship_type'])) ? $_POST['relationship_type'] : NULL;
        $family_name = ($is_for_self == 0 && isset($_POST['family_name'])) ? $_POST['family_name'] : NULL;

        // Check if the selected date and time are already booked
        $check_sql = "SELECT * FROM appointments WHERE date = ? AND time = ? AND doctor_id = ?";
        if ($check_stmt = mysqli_prepare($link, $check_sql)) {
            mysqli_stmt_bind_param($check_stmt, "ssi", $formatted_date, $time, $doctor_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);

            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                echo "<script>
                    alert('The selected date and time are already booked. Please choose another time.');
                    window.location.href = 'appointment.php';
                </script>";
                    
                mysqli_stmt_close($check_stmt);
            } else {
                mysqli_stmt_close($check_stmt);

                $sql = "INSERT INTO appointments (patients_id, doctor_id, admin_id, date, time, is_for_self, relationship_type, family_name, medical_condition) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "iiississs", $patients_id, $doctor_id, $admin_id, $formatted_date, $time, $is_for_self, $relationship_type, $family_name, $medical_condition);
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
            }
        } else {
            echo "ERROR: Could not prepare query: $check_sql. " . mysqli_error($link);
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

        footer {
            font-weight: normal; 
        }

        h1 {
            text-align: center;
            margin: 20px 0 80px 0;
        }

        .required {
            color: red;
            margin-left: 5px; 
        }

        .container {
            display: flex; 
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #F1EDE2;
            padding: 50px 20px;
            height: calc(130vh - 100px); 
        }

        .content-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
        }

        .calendar {
            width: 100%;
            max-width: 500px;
        }

        .ui-datepicker {
            font-size: 1.5em; 
        }

        #timeslot {
            padding: 18px;
            font-size: 18px;
            border-radius: 8px;
            width: 150px;
            background-color: #ffffff; 
            border: 2px solid black;
        }

        .timeslot-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
            font-size: 20px; 
        }

        /* Custom styles for the family info (joc) */
        .field-container {
            display: flex;
            flex-wrap: wrap; 
            align-items: center;
            margin-bottom: 20px;
            justify-content: space-between; 
        }

        .field-container label {
            flex-basis: 100%; 
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 5px; 
        }

        .field-container input[type="text"],
        .field-container select {
            flex-grow: 1; /* Allows the input field to fill the available space */
            padding: 8px;
            border-radius: 8px;
            background-color: #ffffff; 
            border: 2px solid black; 
            font-size: 15px;
            width: 100%; /* Forces the input to take full width of the line */
            box-sizing: border-box; 
        }

        /* Add custom styles for the textarea (joc) */
        #medical-conditions {
            padding: 10px; 
            font-size: 17px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: 100%;
            box-sizing: border-box; 
            background-color: #ffffff; 
            border: 2px solid black;
        }

        select[disabled] {
            padding: 10px; 
            font-size: 17px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: 100%;
            box-sizing: border-box; 
            background-color: #ffffff; 
            border: 2px solid black;
            opacity: 0.5; 
            cursor: not-allowed; 
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
            margin-top: 50px; 
            transition: background-color 0.1s ease;
        }

        .btn-book:hover {
            background-color: #6b2c27;
        }

        /* Navigation Bar Styling (joc) */ 
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
        <a class="navbar-brand text-dark" href="home.php">
            <img src="images/logo.jpeg" alt="logo" class="logo">
        </a>
        <div class="navbar-links">
            <a href="home.php">Home<span class="underline"></span></a>
            <a href="aboutUs.php">About Us<span class="underline"></span></a>
            <a href="appointment.php" class="current">Appointment</a>
            <?php if (isset($_SESSION['username'])): ?>
            <a href="viewAppointment.php">View Appointment<span class="underline"></span></a>
            <?php else: ?>
                <?php endif; ?>
            <a href="contact.php">Contact Us<span class="underline"></span></a>
        </div>

        <!-- Sign Up & Login Button -->
        <?php if (isset($_SESSION['username'])): ?>
    <?php if ($_SESSION['username'] === 'doctor' || $_SESSION['username'] === 'admin'): ?>
        <p style='margin-top: 17px;'>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <?php else: ?>
        <p style='margin-top: 17px;'>Welcome, <a href='userProfile.php' style='text-decoration: underline; color: white;'><?php echo htmlspecialchars($_SESSION['username']); ?></a>!</p>
    <?php endif; ?>
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

    <div class="container">
        <?php if (isset($_SESSION['loggedin']) || isset($_SESSION['loggedin']) == true) { ?> <!-- Check if user is logged in (joc) -->
        
            <h1>Schedule Doctor Appointment</h1>
            <div class="content-wrapper">

                <!-- jQuery UI Datepicker -->
                <div id="calendar-container" class="calendar"></div>

                <form action="appointment.php" method="post" class="timeslot-container">

                    <!-- Timeslot dropdown -->
                    <label for="timeslot"><b>Select time slot:<span class="required">*</span><b></label>
                    <input type="hidden" name="date" id="selected-date" required />
                    <select id="timeslot" name="timeslot" required>
                        <option value="10:30 AM">10:30 AM</option>
                        <option value="10:45 AM">10:45 AM</option>
                        <option value="11:00 AM">11:00 AM</option>
                        <option value="11:15 AM">11:15 AM</option>
                        <option value="11:30 AM">11:30 AM</option>
                        <option value="11:45 AM">11:45 AM</option>
                        <option value="12:00 PM">12:00 PM</option>
                        <option value="12:15 PM">12:15 PM</option>
                        <option value="12:30 PM">12:30 PM</option>
                        <option value="12:45 PM">12:45 PM</option>
                        <option value="1:00 PM">13:00 PM</option>
                        <option value="1:15 PM">13:15 PM</option>
                        <option value="1:30 PM">13:30 PM</option>
                        <option value="1:45 PM">13:45 PM</option>
                        <option value="2:00 PM">14:00 PM</option>
                        <option value="2:15 PM">14:15 PM</option>
                        <option value="2:30 PM">14:30 PM</option>
                        <option value="2:45 PM">14:45 PM</option>
                        <option value="3:00 PM">15:00 PM</option>
                        <option value="3:15 PM">15:15 PM</option>
                        <option value="3:30 PM">15:30 PM</option>
                        <option value="3:45 PM">15:45 PM</option>
                        <option value="4:00 PM">16:00 PM</option>
                        <option value="4:15 PM">16:15 PM</option>
                    </select>

                    <br><br><br> <!-- Add booking for myself/family member (joc) -->

                    <!-- Self/Family member radio button -->
                    <label id="booking_label"><b>Booking for:<span class="required">*</span></b></label> 
                    <br><br>
                    <div>
                        <input type="radio" id="for_self" name="booking_for" value="self" checked required>
                        <label for="for_self" style= "font-size: 17px;">Myself</label>
                        <input type="radio" id="for_family" name="booking_for" value="family" required>
                        <label for="for_family" style= "font-size: 17px;">Family Member</label>
                    </div>
                    <br>

                    <!-- Textfields family member name & dropdown list relationship type-->
                    <div id="family_info" style="display: none;">
                        <div class="field-container">
                            <label for="family_name"><b>Family Member's Name:<span class="required">*</span></b></label>
                            <input type="text" id="family_name" name="family_name" placeholder="Enter family member's name">
                        </div>

                        <div class="field-container">
                            <label for="relationship"><b>Relationship:<span class="required">*</span></b></label>
                            <select id="relationship" name="relationship_type">
                                <option value="Spouse">Spouse</option>
                                <option value="Child">Child</option>
                                <option value="Parent">Parent</option>
                                <option value="Other">Other</option>
                            </select> 
                        </div>
                    </div>

                    <br><br>

                    <!-- Dropdown list medical condition (joc) -->
                    <label for="medical-conditions" id="medical_conditions_label"><b>Reason for consult (Medical Condition):<span class="required">*</span><b></label>
                    <br><br>
                    <select id="medical-conditions" name="medical_conditions" required>
                        <option value="">Select Condition</option>
                        <option value="Cold Flu">Cold/Flu</option>
                        <option value="Digestive Issues">Digestive Issues</option>
                        <option value="Pain Management">Pain Management</option>
                        <option value="Stress Anxiety">Stress/Anxiety</option>
                        <option value="Sleep Disorders">Sleep Disorders</option>
                        <option value="Allergies">Allergies</option>
                        <option value="Others">Others</option>
                    </select>

                    <!-- Book button -->
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
                    <option value=""></option>


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
                    <select id="medical-conditions-disabled" disabled>
                        <option value=""></option>
                    </select>

                    
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

        // jQuery UI Datepicker (joc)
        $(function() {
            $("#calendar-container").datepicker({
                inline: true,
                minDate: 0, // Restrict to today and future dates
                dateFormat: "yy-mm-dd", // Set the date format to YYYY-MM-DD
                beforeShowDay: function(date) {
                    var day = date.getDay(); // Get the day of the week (0 - Sunday, 1 - Monday, ...)
                    // Disable Sundays and Mondays
                    return [(day !== 0 && day !== 1), "", "No appointments on Sundays and Mondays"];
                },
                onSelect: function(dateText) {
                    $("#selected-date").val(dateText); // Set the formatted date
                    updateTimeslotDropdown(dateText); // Update timeslot dropdown based on the selected date
                }
            });

            function updateTimeslotDropdown(selectedDate) {
                var currentDate = new Date();
                var selectedDate = new Date(selectedDate);
                var isToday = (selectedDate.toDateString() === currentDate.toDateString());
                var dayOfWeek = selectedDate.getDay();

                var timeslotOptions = [];
                if (dayOfWeek >= 0 && dayOfWeek <= 5) { // Tuesday to Friday
                    timeslotOptions = ["11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM", "12:00 PM", "12:15 PM", "12:30 PM", "12:45 PM", "13:00 PM", "13:15 PM", "13:30 PM", "13:45 PM", "14:00 PM", "14:15 PM", "14:30 PM", "14:45 PM", "15:00 PM", "15:15 PM", "15:30 PM", "15:45 PM", "16:00 PM", "16:15 PM"];
                } else if (dayOfWeek === 6) { // Saturday
                    timeslotOptions = ["10:30 AM", "10:45 AM", "11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM", "12:00 PM", "12:15 PM", "12:30 PM", "12:45 PM", "13:00 PM", "13:15 PM", "13:30 PM", "13:45 PM", "14:00 PM", "14:15 PM", "14:30 PM", "14:45 PM", "15:00 PM", "15:15 PM", "15:30 PM", "15:45 PM", "16:00 PM", "16:15 PM"];
                }

                // Filter out timeslots that have passed if the selected date is today (joc)
                if (isToday) {
                    var currentHours = currentDate.getHours(); // Get the current hours
                    var currentMinutes = currentDate.getMinutes(); // Get the current minutes
                    timeslotOptions = timeslotOptions.filter(function(timeslot) { // Filter out timeslots that have passed
                        var [hour, minutesPart] = timeslot.split(':'); // Split the timeslot into hour and minutes
                        var minutes = minutesPart.split(' ')[0]; // Extract the minutes
                        var period = minutesPart.split(' ')[1]; // Extract the period such as (AM/PM)
                        var hour24 = hour % 12 + (period === 'PM' ? 12 : 0); // Convert to 24-hour format
                        return hour24 > currentHours || (hour24 === currentHours && minutes > currentMinutes); // Filter out timeslots that have passed
                    });
                }

                var select = $("#timeslot");
                select.empty(); // Clear previous options
                timeslotOptions.forEach(function(time) {
                    select.append($("<option>", { value: time, text: time }));
                });
            }
        });




        
        // Add event listener to show family info when booking for family (joc)
        document.querySelectorAll('input[name="booking_for"]').forEach(radio => { // Loop through each radio button
            radio.addEventListener('change', function() { // Add change event listener
                const familyInfo = document.getElementById('family_info'); 
                const familyNameInput = document.getElementById('family_name'); 
                if (this.value === 'family') { // Show family info if booking for family
                    familyInfo.style.display = 'block'; // Display the family info
                    familyNameInput.required = true; // Make the family name required if family is selected
                } else {
                    familyInfo.style.display = 'none'; // Hide family info if booking for self
                    familyNameInput.required = false; // Not required if booking for self
                }
            });
        });

        function confirmBook() {
            if (confirm("Are you sure you want to edit your profile?")) {
                window.location.href = 'doEditProfile.php';
            }
        }



        // Add event listener to show family info when the radio button is checked as "family" (joc)
        document.addEventListener("DOMContentLoaded", function() { // Wait for the document to load
            const form = document.querySelector("form.timeslot-container"); // Get the form element

            form.addEventListener("submit", function(event) { // Add submit event listener to the form
                let isValid = true; // Assume the form is valid by default
                const selectedDate = document.getElementById("selected-date").value; 
                const timeslot = document.getElementById("timeslot").value; 
                const bookingFor = document.querySelector('input[name="booking_for"]:checked'); 
                const medicalConditions = document.getElementById("medical-conditions").value; 
                const familyName = document.getElementById("family_name").value; 
                const relationshipType = document.getElementById("relationship"); 

                // Check if all required fields are filled
                if (!selectedDate || !timeslot || !bookingFor || !medicalConditions || (bookingFor.value === "family" && (!familyName || !relationshipType.value))) {
                    alert("Please fill in all required fields.");
                    isValid = false;
                }

                // Check if the family name is filled if booking for family
                if (!isValid) {
                    event.preventDefault(); // Prevent form submission
                    return false;
                }
                return true;
            });
        });


    </script>
</body>
</html>
