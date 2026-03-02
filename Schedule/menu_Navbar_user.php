<?php include 'component_user.php';
session_start();?>
<style>
    .result-button {
        padding: 0px 5px;
    }
</style>
<?php
require('dbconnect.php');
if (empty($_SESSION['user_id'])) {
  echo '<script>
  setTimeout(function() {
  swal({
  title: "คุณไม่มีสิทธิ์ใช้งานหน้านี้",
  type: "error"
  }, function() {
    window.location = "signin_signup.php"; //หน้าที่ต้องการให้กระโดดไป
  });
  }, 1000);
  </script>';
  exit();

}
else {$user_id = $_SESSION["user_id"];};
$sql = "SELECT * FROM userlogin WHERE user_id = $user_id ";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<body>
  
             <!-- ***** Header Area Start ***** -->
  <header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <!-- ***** Logo Start ***** -->
            <a href="indexuser.php" class="logo my-4">
              <img src="assets/images/Schedule_logo.png" alt="">
            </a>
            <!-- ***** Logo End ***** -->
            <!-- ***** Menu Start ***** -->
            <ul class="nav" style="font-family: 'Noto Sans Thai', sans-serif;">
                <li class="scroll-to-section"><a href="indexuser.php" class="active">หน้าแรก</a></li>
                <li class="scroll-to-section"><a href="schedule_user.php">สร้างกำหนดการ</a></li>
                <li class="scroll-to-section"><a href="form_sendemail_friend.php">กำหนดการเร่งด่วน</a></li>
                <li class="scroll-to-section"><a href="friend_user.php">เพื่อน</a></li>
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle result-button" href="#" id="resultDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">รายละเอียด</a>
                    <div class="dropdown-menu" aria-labelledby="resultDropdown">
                        <a class="dropdown-item" href="result.php">กำหนดการของคุณ</a>
                        <a class="dropdown-item" href="result_with_friend.php">กำหนดการกับเพื่อน</a>
                        <a class="dropdown-item" href="your_calendar.php">กำหนดการของคุณ(แบบปฏิทิน)</a>
                        <a class="dropdown-item" href="calendar_with_friend.php">กำหนดการกับเพื่อน(แบบปฏิทิน)</a>
                        <a class="dropdown-item" href="result_calendar.php">สรุปผล</a>
                        <!-- <a class="dropdown-item" href="calendar.php">calendar</a> -->
                        
                    </div>
                </li>
                
                
                <!-- <li class="scroll-to-section"><a href="#blog">Blog</a></li>
                <li class="scroll-to-section"><a href="#contact">Contact</a></li>  -->
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle result-button" href="#" id="resultDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $row['name'] ?></a>
                    <div class="dropdown-menu" aria-labelledby="resultDropdown">
                      <a class="dropdown-item" href="profile_user.php">โปรไฟล์</a>
                        <a class="dropdown-item" href="logout.php">ออกจากระบบ</a>
                    </div>
                </li>
              
                <img src="<?php echo $row['imgg']? $row['imgg'] : 'assets/images/person.png'; ?>" style="width: 40px; height: 40px; border-radius: 50%;" alt="">
            </ul>        
            <a class='menu-trigger'>
                <span>Menu</span>
            </a>
            <!-- ***** Menu End ***** -->
            
          </nav>
        </div>
      </div>
    </div>
  </header>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/animation.js"></script>
  <script src="assets/js/imagesloaded.js"></script>
  <script src="assets/js/custom.js"></script>

</body>
</html>