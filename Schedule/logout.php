<?php include('component_user.php');
    session_start();?>
<?php
    // Destroy the session
    session_destroy();
    
    echo '<script>
            setTimeout(function() {
             swal({
                 title: "ออกจากระบบสำเร็จ",
                 text: "successfully",
                 type: "success"
             }, function() {
                 window.location.href="signin_signup.php";
             });
           }, 500);
          </script>';
    // header("Location: signin_signup.php");
    exit;
?>