<?php 
session_start(); // Start the session

include 'dbfunctions.php';

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
            border-radius: px;
            border: 1px solid #ccc;
            width: 150px;
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
            padding: 15px 25px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px; /* Adjust margin-top to create space */
        }

        h1 {
            text-align: center;
            margin-top: -250px;
            margin-bottom: 80px;
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

    <div class="container">
        <h1>Schedule Doctor Appointment</h1>
        <div class="content-wrapper">
            <div id="calendar-container" class="calendar"></div>
            <div class="timeslot-container">
                <label for="timeslot"><b>Select time slot:<b></label>
                <select id="timeslot">
                    <option value="10:30 AM">10:30 AM</option>
                    <option value="11:00 AM">11:00 AM</option>
                    <option value="12:00 PM">12:00 PM</option>
                    <option value="1:00 PM">1:00 PM</option>
                    <option value="2:00 PM">2:00 PM</option>
                    <option value="3:00 PM">3:00 PM</option>
                    <option value="4:00 PM">4:00 PM</option>
                    <option value="5:00 PM">5:00 PM</option>
                </select>
                <button class="btn-book">Book</button>
            </div>
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
    $(function() {
        $("#calendar-container").datepicker({
            inline: true,
            minDate: 0, // Restrict to today and future dates
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
                        validTimeSlots = ["10:30 AM", "10:45 AM", "11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM", "12:00 PM", "12:15 PM", "12:30 PM", "12:45 PM", "1:00 PM", "1:15 PM", "1:30 PM", "1:45 PM", "2:00 PM", "2:15 PM", "2:30 PM", "2:45 PM", "3:00 PM", "3:15 PM", "3:30 PM", "3:45 PM", "4:00 PM", "4:15 PM", "4:30 PM"];
                        break;
                    default:
                        break;
                }

                // Check if it's a valid day
                var valid = (validTimeSlots.length > 0);

                return [valid];
            },
            onSelect: function(dateText, inst) {
                var selectedDate = $(this).datepicker('getDate');
                var dayOfWeek = selectedDate.getDay();
                var validTimeSlots = [];

                // Populate validTimeSlots based on the selected day
                switch (dayOfWeek) {
                    case 2: // Tuesday
                    case 3: // Wednesday
                    case 4: // Thursday
                    case 5: // Friday
                        validTimeSlots = ["11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM", "12:00 PM", "12:15 PM", "12:30 PM", "12:45 PM", "1:00 PM", "1:15 PM", "1:30 PM", "1:45 PM", "2:00 PM", "2:15 PM", "2:30 PM", "2:45 PM", "3:00 PM", "3:15 PM", "3:30 PM", "3:45 PM", "4:00 PM", "4:15 PM", "4:30 PM"];
                        break;
                    case 6: // Saturday
                        validTimeSlots = ["10:30 AM", "10:45 AM", "11:00 AM", "11:15 AM", "11:30 AM", "11:45 AM", "12:00 PM", "12:15 PM", "12:30 PM", "12:45 PM", "1:00 PM", "1:15 PM", "1:30 PM", "1:45 PM", "2:00 PM", "2:15 PM", "2:30 PM", "2:45 PM", "3:00 PM", "3:15 PM", "3:30 PM", "3:45 PM", "4:00 PM", "4:15 PM", "4:30 PM"];
                        break;
                    default:
                        break;
                }

                // Update the timeslot dropdown based on the selected date
                var timeslotDropdown = $("#timeslot");
                timeslotDropdown.empty();
                $.each(validTimeSlots, function(index, value) {
                    timeslotDropdown.append($('<option>').text(value).attr('value', value));
                });
            }
        });
    });
</script>

</body>
</html>