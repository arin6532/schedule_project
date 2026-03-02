<?php include('component_user.php');
session_start();?>
<?php
require('dbconnect.php');
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['pwd'];
    $email = $_POST['email'];

    // Update user profile data in the database
    $sql = "UPDATE userlogin SET name = '$name', username = '$username', pwd = '$password', email = '$email' WHERE user_id = $user_id";

    if ($con->query($sql) === TRUE) {
        echo '<script>
        setTimeout(function() {
         swal({
             title: "แก้ไขเรียบร้อย",
              text: "Successfully",
             type: "success"
         }, function() {
             window.location.href="profile_user.php";
         });
       }, 1000);
      </script>';
    } else {
        echo '<script>
            setTimeout(function() {
             swal({
                 title: "เกิดข้อผิดพลาด",
                  text: "error",
                 type: "error"
             }, function() {
                 window.location.href="profile_user.php";
             });
           }, 1000);
          </script>' . $con->error;
    }

    // Process uploaded profile image
    if ($_FILES['imgg']['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'assets/profile_images/'; // Specify the directory to upload images
        $targetFile = $targetDir . basename($_FILES['imgg']['name']);
        move_uploaded_file($_FILES['imgg']['tmp_name'], $targetFile);

        // Update the user's image path in the database
        $updateImageQuery = "UPDATE userlogin SET imgg = '$targetFile' WHERE user_id = $user_id";
        if ($con->query($updateImageQuery) === TRUE) {
            echo '<script>
            setTimeout(function() {
             swal({
                 title: "แก้ไขเรียบร้อย",
                  text: "Successfully",
                 type: "success"
             }, function() {
                 window.location.href="profile_user.php";
             });
           }, 1000);
          </script>';
        } else {
            echo '<script>
            setTimeout(function() {
             swal({
                 title: "เกิดข้อผิดพลาด",
                  text: "error",
                 type: "error"
             }, function() {
                 window.location.href="profile_user.php";
             });
           }, 1000);
          </script>' . $con->error;
        }
    }
}

$con->close();
?>