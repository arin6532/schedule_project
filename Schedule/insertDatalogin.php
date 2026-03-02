<?php include('component_user.php');
session_start();
?>
<?php
if(isset($_POST['username'])){
          include("dbconnect.php");
          $username = $_POST['username'];
          $pwd = $_POST['password'];
          // echo $username ;
          // echo $pwd ;

          $sql="SELECT * FROM userlogin
          WHERE  username='".$username."' AND pwd='".$pwd."' ";
          $result = mysqli_query($con,$sql);
 
          if(mysqli_num_rows($result)==1){
            $row = mysqli_fetch_array($result);
            $_SESSION["username"] = $row["username"];
            $_SESSION["user_id"] = $row["user_id"];
            echo '<script>
            setTimeout(function() {
             swal({
                 title: "เข้าสู่ระบบสำเร็จ",
                  text: "ยินดีต้อนรับ : '. $row['name'].'",
                 type: "success"
             }, function() {
                 window.location.href="indexuser.php";
             });
           }, 500);
          </script>';
          }else{
            echo '<script>
            setTimeout(function() {
             swal({
                 title: "ขออภัย",
                  text: "Username หรือ Password ไม่ถูกต้อง ลองใหม่อีกครั้ง",
                 type: "error"
             }, function() {
                 window.history.back();
             });
           }, 500);
          </script>';

        }
}else{ 
  
}

?>