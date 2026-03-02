<?php include('component_user.php');?>
<?php
//เชื่อมต่อฐานข้อมูล
require('dbconnect.php');
    $name=$_POST["name"];
    $username=$_POST["username"];
    $pwd=$_POST["pwd"];
    $email=$_POST["email"];
//เช็คข้อมูลซ ้าหรือไม่
$check = "SELECT * FROM userlogin WHERE username='$username' AND pwd ='$pwd' ";

    $resultchk =mysqli_query($con,$check);
    $numchk = mysqli_num_rows($resultchk);
    if ($numchk>0) {
        echo '<script>
            setTimeout(function() {
                swal({
                    title: "เกิดข้อผิดพลาด",
                     text: "Repeat Password",
                    type: "warning"
                }, function() {
                   window.history.back();
             });
           }, 1000);
          </script>';
        exit(0);
        }
        else {
        // $sql="INSERT INTO userlogin (name,username,pwd)
        $sql="INSERT INTO userlogin (name,username,pwd,email)
        VALUES ('$name','$username','$pwd','$email')";
        //echo $sql
        $result =mysqli_query($con,$sql);
        if ($result) {
            echo '<script>
            setTimeout(function() {
             swal({
                 title: "สมัครสมาชิกสำเร็จ",
                  text: "Complete To Regiser",
                 type: "success"
             }, function() {
                    window.location.href = "signin_signup.php";
             });
           }, 1000);
          </script>';
        exit(0);
        }
        else
        mysqli_error($con);
        }
?>