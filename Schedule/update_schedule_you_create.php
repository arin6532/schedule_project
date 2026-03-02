<?php 
session_start();
include('component_user.php'); 

require('dbconnect.php');
// ตรวจสอบการส่งข้อมูลโดยใช้ HTTP POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลที่ส่งมาจากฟอร์ม
    $sch_id = $_POST['sch_id'];
    $sch_name = mysqli_real_escape_string($con, $_POST['sch_name']);
    $sch_checkbox_values = $_POST['sch_checkbox']; // รับค่ามาจากฟอร์ม
    $sch_checkbox = implode(',', $sch_checkbox_values);
    $sch_description = mysqli_real_escape_string($con, $_POST['sch_description']);
    $time_sch = $_POST['time_sch'];
    $end_time_sch = $_POST['end_time_sch'];
    $time_create_schedule = $_POST['time_create_schedule'];

    // สร้างคำสั่ง SQL สำหรับอัปเดตข้อมูล
    $sql = "UPDATE schedule SET sch_name='$sch_name', sch_checkbox='$sch_checkbox', sch_description='$sch_description', time_sch='$time_sch', time_create_schedule='$time_create_schedule', end_time_sch='$end_time_sch' WHERE sch_id='$sch_id'";

    // ทำการอัปเดตข้อมูล
    if (mysqli_query($con, $sql)) {
        echo '<script>
            setTimeout(function() {
            swal({
                title: "Information Update",
                text: "บันทึกสำเร็จ",
                type: "success"
            }, function() {
                window.location.href="result.php";
            });
        }, 1000);
        </script>';
    } else {
        echo '<script>
            setTimeout(function() {
            swal({
                title: "Information Update",
                text: "เกิดข้อผิดพลาด",
                type: "error"
            }, function() {
                window.location.href="result.php";
            });
        }, 1000);
        </script>' . $con->error;
    }

    // ปิดการเชื่อมต่อกับฐานข้อมูล
    mysqli_close($con);
}
?>
