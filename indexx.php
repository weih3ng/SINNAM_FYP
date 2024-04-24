<?php
// Include the database connection file
include "dbfunctions.php";
// Query to select all patients
$sql = "SELECT * FROM patients";
$result = mysqli_query($link, $sql);

// Check if there are any patients
if (mysqli_num_rows($result) > 0) {
    // Output data of each patient
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row["id"] . "<br>";
        echo "Name: " . $row["name"] . "<br>";
        echo "Age: " . $row["age"] . "<br>";
        echo "Gender: " . $row["gender"] . "<br>";
        echo "Email: " . $row["email"] . "<br><br>";
    }
} else {
    echo "No patients found";
}

// Close connection
mysqli_close($link);
?>

