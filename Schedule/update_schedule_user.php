<?php 
include('component_user.php'); 
session_start();
use PHPMailer\PHPMailer\PHPMailer;
?>
<?php
require('dbconnect.php');

// // Get data from the form
$sch_name = $_POST['sch_name'];
$sch_description = $_POST['sch_description'];
$type_noti = $_POST['Dine']; // รับค่าที่มาจากฟอร์ม (1 หรือ 2)
$time_sch = $_POST['time_sch'];
$end_time_sch = $_POST['end_time_sch'];

// // Convert sch_checkbox to JSON and escape it to prevent SQL injection
// // เช็คว่ามีการส่งค่า sch_checkbox จากฟอร์มหรือไม่
if(isset($_POST['sch_checkbox'])){
    // ใช้ implode เพื่อรวมค่า checkbox ที่ถูกเลือกเป็น string ด้วยเครื่องหมาย ','
    $sch_checkbox = implode(',', $_POST['sch_checkbox']);
} else {
    // ถ้าไม่มีการเลือก checkbox ให้กำหนดค่าเป็นค่าเริ่มต้นที่คุณต้องการ
    $sch_checkbox = '0';
}

// // Assuming you have $_SESSION["user_id"] that stores the UID of the user
$user_id = $_SESSION["user_id"];

// Check if friendIds array is set
if(isset($_POST['friendId'])){
    // Get friendId from the form as an array
    $friendIds = $_POST['friendId']; // Assuming you have a field named 'friendId' in your form
} else {
    // If friendIds is not set, create an empty array
    $friendIds = array();
}
// print_r ($friendIds);
// // Insert data into the 'schedule' table
$query = "INSERT INTO schedule (sch_name, sch_checkbox, sch_description, type_noti, time_sch, sch_id_sender, end_time_sch) VALUES ('$sch_name', '$sch_checkbox', '$sch_description', '$type_noti', '$time_sch', '$user_id', '$end_time_sch')";

if (mysqli_query($con, $query)) {
    $schedule_id = mysqli_insert_id($con); // Get the ID of the newly inserted schedule

    // Insert the sender's ID into the schedule_friend_sender table for each selected friend
    foreach ($friendIds as $friendId) {
        $query = "INSERT INTO schedule_friend (schedule_friend_id, schedule_friend_sender, schedule_friend_recipient) VALUES ('$schedule_id', '$user_id', '$friendId')";

        if (mysqli_query($con, $query)) {
            // Inserted sender's ID successfully for this friend
        } else {
            // Error inserting sender's ID for this friend
            echo "Error: " . mysqli_error($con);
        }
    }

    // ตรวจสอบว่ามีไฟล์ถูกอัปโหลดหรือไม่
    if (isset($_FILES['schedule_file'])) {
        // วนลูปผ่านไฟล์ที่อัปโหลด
        foreach ($_FILES['schedule_file']['name'] as $key => $file_name) {
            $file_tmp_name = $_FILES['schedule_file']['tmp_name'][$key];
            $file_size = $_FILES['schedule_file']['size'][$key];

            // ตรวจสอบขนาดของไฟล์ (คุณสามารถปรับขนาดได้ตามต้องการ)
            $max_file_size = 5 * 1024 * 1024; // 5 MB
            if ($file_size <= $max_file_size) {
                // ย้ายไฟล์ที่อัปโหลดไปยังโฟลเดอร์ที่กำหนด
                $upload_folder = 'uploads_file_schedule/'; // เปลี่ยนเป็นโพลเดอร์ที่คุณต้องการ
                $uploaded_file_path = $upload_folder . $file_name;

                if (move_uploaded_file($file_tmp_name, $uploaded_file_path)) {
                    // บันทึกข้อมูลไฟล์ลงในตาราง 'schedule_file' พร้อมกับการเชื่อมโยงกับตาราง 'schedule'
                    $query = "INSERT INTO schedule_file (file_path, sch_file_id, schedule_file_name) VALUES ('$uploaded_file_path', '$schedule_id', '$file_name')";

                    if (mysqli_query($con, $query)) {
                        // File uploaded successfully
                    } else {
                        // Error creating schedule_file
                    }
                } else {
                    // Error uploading file
                }
            } else {
                // File size exceeds the limit
            }
        }
    }

    // Schedule created successfully, show SweetAlert
    echo '<script>
            setTimeout(function() {
            swal({
                title: "บันทึกสำเร็จ",
                text: "Successfully",
                type: "success"
            }, function() {
                window.location.href="schedule_user.php";
            });
        }, 1000);
        </script>';


        // Check if the query was successful
        
        $friendIdsString = implode(',', $friendIds);

        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";
        
        // Create a PHPMailer object
        $mail = new PHPMailer(true);
        
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Update SMTP server as needed
        $mail->SMTPAuth = true;
        $mail->Username = 'hunzk26124409@gmail.com'; // Your email address
        $mail->Password = 'bdufsyaqwghfghcd'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use ENCRYPTION_SMTPS if required
        $mail->Port = 587; // Update port as needed
        
        // Email sender information
        $mail->setFrom('hunzk26124409@gmail.com', 'Schedule.com'); // Your name and email address
        
        // Email body settings
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        
        $mail->Subject = 'Hello';

        foreach ($friendIds as $friendId) {
            // Reset the recipients for each email
            $mail->clearAddresses();
        
            // Prepare a query for each friendId
            $Query = "SELECT DISTINCT email
                      FROM schedule
                      LEFT JOIN schedule_file ON schedule.sch_id = schedule_file.sch_file_id
                      JOIN schedule_friend ON schedule.sch_id = schedule_friend.schedule_friend_id
                      JOIN userlogin ON schedule_friend.schedule_friend_recipient = userlogin.user_id
                      WHERE schedule_friend_recipient = $friendId";
        
            $Result = mysqli_query($con, $Query);
        
            if (!$Result) {
                die("Query failed: " . mysqli_error($con));
            }
        
            // Loop through query results and send an email to each recipient
            while ($row = mysqli_fetch_assoc($Result)) {
                $email = $row['email'];
        
                // Set the recipient
                $mail->addAddress($email);
        
                // Email body content
                $mail->Body = 'A new schedule has been added to you.';
        
                // Send email
                if (!$mail->send()) {
                    echo "Error sending email to $email: " . $mail->ErrorInfo;
                } else {
                    // echo "Email sent successfully to $email<br>";
                }
            }
        }
        



        // echo $id . "<br>";

} else {
    // Error creating schedule
    echo '<script>
            setTimeout(function() {
            swal({
                title: "เกิดข้อผิดพลาด",
                text: "error",
                type: "error"
            }, function() {
                window.location.href="schedule_user.php";
            });
        }, 1000);
        </script>' . $con->error;
}

// Close the database connection
mysqli_close($con);
?> 