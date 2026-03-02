<?php
session_start();
// Include your database connection setup
require('dbconnect.php');

// Assuming you have $_SESSION["user_id"] that stores the UID of the user
// Check if the schedule_id parameter exists in the URL
$userId = $_SESSION["user_id"];
if (isset($_GET['sch_id']) && is_numeric($_GET['sch_id'])) {
    
    // Get the schedule_id from the URL
    $scheduleId = $_GET['sch_id'];

    // Perform the deletion query for schedule table
    $deleteQuery = "DELETE FROM schedule WHERE sch_id = '$scheduleId' ";
    $deleteResult = mysqli_query($con, $deleteQuery);

    if (mysqli_affected_rows($con) > 0) {
        // Deletion from schedule table was successful
        // You can perform additional actions or redirection here if needed
    } else {
        // Error deleting from schedule table
        echo "Error: " . mysqli_error($con);
    }

    // Perform the deletion query for schedule_file table
    $deleteQuery1 = "DELETE FROM schedule_file WHERE sch_file_id = '$scheduleId'";
    $deleteResult1 = mysqli_query($con, $deleteQuery1);

    if (mysqli_affected_rows($con) > 0) {
        // Deletion from schedule_file table was successful
        // You can perform additional actions or redirection here if needed
    } else {
        // Error deleting from schedule_file table
        echo "Error: " . mysqli_error($con);
    }

    // Perform the deletion query for schedule_friend table
    $deleteQuery2 = "DELETE FROM schedule_friend WHERE schedule_friend_id = '$scheduleId'";
    $deleteResult2 = mysqli_query($con, $deleteQuery2);

    if (mysqli_affected_rows($con) > 0) {
        // Deletion from schedule_friend table was successful
        // You can perform additional actions or redirection here if needed
        echo "success ";
    } else {
        // Error deleting from schedule_friend table
        echo "Error: " . mysqli_error($con);
    }

    // Redirect back to the friend list page after all deletions (or after individual deletions if needed)
    header("Location: result.php");
    exit();
} else {
    // Invalid or missing schedule_id parameter
    echo "Invalid or missing schedule ID.";
}
?>
