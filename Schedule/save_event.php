<?php 
include('component_user.php'); 
session_start();
?>
<?php 
require('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user ID from the session
    $id_user_calendar = $_SESSION['user_id'];
    
    // Retrieve form data, ensuring proper escaping to prevent SQL injection
    $calendar_name = mysqli_real_escape_string($con, $_POST['calendar_name']);
    $calendar_start_date = mysqli_real_escape_string($con, $_POST['calendar_start_date']);
    $calendar_end_date = mysqli_real_escape_string($con, $_POST['calendar_end_date']);

    // Define the SQL query for insertion
    $sql = "INSERT INTO mark_calendar (id_user_calendar, calendar_name, calendar_start_date, calendar_end_date) 
            VALUES ('$id_user_calendar', '$calendar_name', '$calendar_start_date', '$calendar_end_date')";

    // Perform the database query
    if ($con->query($sql) === TRUE) {
        echo '<script>
        setTimeout(function() {
         swal({
             title: "Information Update",
              text: "Successfully",
             type: "success"
         }, function() {
             window.location.href="profile_user.php";
         });
       }, 1000);
      </script>';
    } else {
        // Database insertion failed, provide an error message
        echo json_encode(["success" => false, "message" => "Error: " . $con->error]);
    }
} else {
    echo '<script>
    setTimeout(function() {
     swal({
         title: "Information Update",
          text: "error",
         type: "error"
     }, function() {
         window.location.href="profile_user.php";
     });
   }, 1000);
  </script>' . $con->error;
}

$con->close();
?>

