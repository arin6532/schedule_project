<?php
session_start();

// Include your database connection setup
require('C:/xampp/htdocs/Schedule/dbconnect.php');

if (isset($_POST['event_id'])) {
    $eventId = $_POST['event_id'];

    // Assuming you have $_SESSION["user_id"] that stores the UID of the user
    $userId = $_SESSION["user_id"];

    // Perform the deletion query for urgent_schedule table
    $deleteQuery = "DELETE FROM urgent_schedule WHERE urgent_id_recipient = '$userId' AND urgent_id_sender = '$eventId'";

    $deleteResult = mysqli_query($con, $deleteQuery);

    if ($deleteResult) {
        // Deletion from urgent_schedule table was successful
        echo "success";
    } else {
        // Error deleting from urgent_schedule table
        echo "Error: " . mysqli_error($con);
    }
}
?>