<?php 
include('menu_Navbar_user.php'); 
require('dbconnect.php');
include('calendar/component_calendar_friend.php'); 
include('calendar/script_calendar_friend.php'); 

$user_id = $_SESSION["user_id"];

// ทำคำสั่ง SQL เพื่อเรียกข้อมูล
$sql = "SELECT COUNT(*) AS total FROM schedule WHERE sch_id_sender = $user_id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$totalSchedules = $row['total'];

$sql1 = "SELECT COUNT(*) AS total1 FROM schedule_friend WHERE schedule_friend_recipient = $user_id";
$result1 = mysqli_query($con, $sql1);
$row1 = mysqli_fetch_assoc($result1);
$totalSchedules1 = $row1['total1'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- เพิ่มลิงก์ไปยังไฟล์ CSS ของ Bootstrap หากยังไม่ได้ทำ -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
</head>


<style>
    .custom-bg {
        background-color: #4f80c0;
    }
    .custom-bg1 {
        background-color: #397d54;
    }
    .custom-bg2 {
        background-color: #FCCF55;
    }
</style>

<!-- <body>  
    
<div style="height: 150px;"></div>
<div class="container d-flex justify-content-center">
    <div class="card text-white custom-bg mb-3 mx-auto" style="max-width: 25rem;">
        <div class="card-header" >All schedules you create</div>
            <div class="card-body text-center">
                <h5 class="card-title" style="font-size: 35px;"><?php echo $totalSchedules; ?></h5>

            </div>
    </div>
    <div class="card text-white custom-bg1 mb-3 mx-auto" style="max-width: 25rem;">
        <div class="card-header text-center">All schedules with friend</div>
        <div class="card-body text-center">
            <h5 class="card-title" style="font-size: 35px;"><?php echo $totalSchedules1; ?></h5>
        </div>
    </div>
    <div class="card text-white custom-bg2 mb-3 mx-auto" style="max-width: 25rem;">
        <div class="card-header">Notification successful</div>
            <div class="card-body text-center">
                <h5 class="card-title" style="font-size: 35px;">8</h5>

            </div>
    </div>
</div>


<!-- เพิ่มลิงก์ไปยังไฟล์ JavaScript ของ Bootstrap หากต้องการใช้ JavaScript -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script> -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
</body>
</html>