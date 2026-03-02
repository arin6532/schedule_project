<?php
// Include your database connection setup
session_start();
require('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["empId"])) {
    $friendId = $_GET["empId"];

    // Get the user ID of the currently logged-in user
    $userId = $_SESSION["user_id"]; // Assuming you have a session variable for the current user's ID

    // Delete friend data from friend_user table
    $deleteQuery = "DELETE FROM friend_user WHERE (id_sender = '$userId' AND id_recipient = '$friendId') OR (id_sender = '$friendId' AND id_recipient = '$userId')";
    $deleteResult = mysqli_query($con, $deleteQuery);

    if ($deleteResult) {
        // Redirect the user to the desired page after successful deletion
        header("Location: friend_user.php");
        exit; // Ensure that no further code is executed
    } else {
        // Error deleting friend data
        echo "Error: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>