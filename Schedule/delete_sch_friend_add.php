<?php
session_start();
require('dbconnect.php');

$userId = $_SESSION["user_id"];
if (isset($_GET['sch_id']) && is_numeric($_GET['sch_id'])) {

    $scheduleId = $_GET['sch_id'];

    $deleteQuery2 = "DELETE FROM schedule_friend WHERE schedule_friend_recipient = '$userId' AND schedule_friend_id = '$scheduleId'";
    $deleteResult2 = mysqli_query($con, $deleteQuery2);

    if ($deleteResult2) {

        echo "success";
    } else {

        echo "Error: " . mysqli_error($con);
    }
} else {

    echo "Invalid or missing schedule ID.";
}
?>