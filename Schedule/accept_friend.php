<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["user_id"])) {
    $friendId = $_GET["user_id"];

    // Include your database connection setup
    require('dbconnect.php');

    // Get the user ID of the currently logged-in user
    $userId = $_SESSION["user_id"]; // Assuming you have a session variable for the current user's ID

    // Update friend_type to 2 (accepted) in friend_user table
    $updateQuery = "UPDATE friend_user SET friend_type = 2 WHERE id_sender = '$friendId' AND id_recipient = '$userId'";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        // Redirect back to the friend list page
        header("Location: request_friend.php");
        exit();
    } else {
        // Error updating friend_type
        echo "Error: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>