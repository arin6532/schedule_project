<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["user_id"])) {
    $friendId = $_GET["user_id"];

    // Include your database connection setup
    require('dbconnect.php');

    // Get the user ID of the currently logged-in user
    $userId = $_SESSION["user_id"]; // Assuming you have a session variable for the current user's ID

    // Delete the friend request from friend_user table
    $deleteQuery = "DELETE FROM friend_user WHERE id_sender = '$friendId' AND id_recipient = '$userId'";
    $deleteResult = mysqli_query($con, $deleteQuery);

    if ($deleteResult) {
        // Redirect back to the friend list page
        header("Location: request_friend.php");
        exit();
    } else {
        // Error deleting friend request
        echo "Error: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>