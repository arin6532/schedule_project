<?php
include('component_user.php');
session_start();
use PHPMailer\PHPMailer\PHPMailer;
?>

<?php
require('dbconnect.php');

$user_id = $_SESSION["user_id"];

if (isset($_POST['name']) && isset($_POST['date']) && isset($_POST['header']) && isset($_POST['detail'])) {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $urgent_end_date = $_POST['end_date'];
    $header = $_POST['header'];
    $detail = $_POST['detail'];
    $friendEmails = $_POST['friendEmail']; // Array of friend emails
    $friendIds = $_POST['friendId']; // Array of friend ids

    $status = "success";
    $response = "Emails sent successfully"; // Default response

    foreach ($friendIds as $friendId) {
        // Insert data into the 'urgent_schedule' table for each selected friend
        $query = "INSERT INTO urgent_schedule (urgent_id_sender, urgent_id_recipient, urgent_title, urgent_description, urgent_time_sch, urgent_end_date) VALUES ('$user_id', '$friendId', '$header', '$detail', '$date', '$urgent_end_date')";
        $result = mysqli_query($con, $query);
        
        if (!$result) {
            $status = "failed";
            $response = "Database insertion failed";
            break;
        }
    }

    // If all database insertions were successful, proceed to send emails
    if ($status === "success") {
        require_once "PHPMailer/PHPMailer.php";
        require_once "PHPMailer/SMTP.php";
        require_once "PHPMailer/Exception.php";

        foreach ($friendEmails as $email) {
            // Create a new PHPMailer object for each friend
            $mail = new PHPMailer();
            
            // Configure SMTP settings
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "hunzk26124409@gmail.com"; // Replace with your Gmail email address
            $mail->Password = "bdufsyaqwghfghcd"; // Replace with your Gmail password
            $mail->Port = 587;
            $mail->SMTPSecure = "tls";

            // Email settings
            $mail->CharSet = "UTF-8"; // Set charset to UTF-8
            $mail->isHTML(true);
            $mail->setFrom($email, $name);
            $mail->addAddress($email); // Recipient email

            // Create email subject with UTF-8 encoding
            $mail->Subject = "=?UTF-8?B?" . base64_encode($header) . "?=";

            // Add date and detail to the email body
            $mail->Body = "วันที่เริ่มกำหนดการ: " . $date . "<br><br>"."วันที่สิ้นสุดกำหนดการ: " . $urgent_end_date. "<br><br>" . "รายละเอียด: " . $detail;

            // Check if files are uploaded
            if (isset($_FILES['file'])) {
                $files = $_FILES['file'];

                // Loop through the files
                for ($i = 0; $i < count($files['name']); $i++) {
                    $attachmentFilePath = $files['tmp_name'][$i];
                    $attachmentFileName = $files['name'][$i];

                    // Add each file as an attachment
                    $mail->addAttachment($attachmentFilePath, $attachmentFileName);
                }
            }

            // Send the email
            if (!$mail->send()) {
                $status = "failed";
                $response = "Email sending failed: " . $mail->ErrorInfo;
                break;
            }
        }
    }

    // ใช้ตัวแปร $status และ $response ที่แก้ไขไว้เพื่อให้ผลลัพธ์ถูกต้อง
    if ($status === "success") {
        $response = "Emails sent successfully";
    } else {
        $response = "Email sending failed: " . $mail->ErrorInfo;
    }
}

// Prepare the response
echo json_encode(array("status" => $status, "response" => $response));
?>