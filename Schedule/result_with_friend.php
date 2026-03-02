<?php include('menu_Navbar_user.php'); ?>

<?php
require('dbconnect.php');

$userId = $_SESSION["user_id"];

$searchKeyword = ""; // Initialize the search keyword variable

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchKeyword = $_POST["search_userid"];
}

$sql = "SELECT sch_id_sender, sch_name, time_sch, time_create_schedule, end_time_sch FROM schedule WHERE sch_id_sender = '$userId'";
$result = mysqli_query($con, $sql);
$num1 = 0;

$sql2 = "SELECT DISTINCT sch_id, schedule_friend_sender, sch_checkbox, sch_description, sf.schedule_friend_recipient, sch_name, time_sch, time_create_schedule, end_time_sch, ul_sender.name AS sender_name, ul_recipient.name AS recipient_name 
         FROM schedule
         LEFT JOIN schedule_file ON schedule.sch_id = schedule_file.sch_file_id
         JOIN schedule_friend sf ON schedule.sch_id = sf.schedule_friend_id
         JOIN userlogin AS ul_sender ON schedule.sch_id_sender = ul_sender.user_id
         JOIN userlogin AS ul_recipient ON sf.schedule_friend_recipient = ul_recipient.user_id
         WHERE sf.schedule_friend_recipient = $userId
         AND (schedule.sch_name LIKE '%$searchKeyword%' OR ul_sender.name LIKE '%$searchKeyword%')"; // Updated SQL query to filter by schedule name and sender name

$result2 = mysqli_query($con, $sql2);
?>

<style>
    .font-thai {
        font-family: 'Kanit', sans-serif;
    }
</style>

<!DOCTYPE html>
<html>

<head>
    <title>Schedule Page</title>
    <style>
        /* Add CSS styles for circular images */
        .circle-image {
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }

        /* Add CSS styles for files */
        .file-container {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Your Schedule -->
    <div style="height: 150px;"></div>
    <div class="card p-3 container font-thai">
        <h2 class="text-center mt-5">กำหนดการของคุณกับเพื่อน</h2>
        <table class="table mt-4">
            <form class="form-group" action="" method="POST">
                <tbody>
                    <tr>
                        <td style="width: 100%;">
                            <input type="text" name="search_userid" placeholder="ค้นหาจาก ID หรือชื่อผู้ส่ง" class="form-control" required value="<?php echo $searchKeyword; ?>">
                        </td>
                        <td>
                            <input type="submit" value="ค้นหา" class="btn btn-success">
                        </td>
                    </tr>
                </tbody>
            </form>
        </table>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ผู้ส่ง</th>
                    <th>ผู้รับ</th>
                    <th>หัวข้อ</th>
                    <th>วันเวลาเริ่มกำหนดการ</th>
                    <th>วันเวลาสิ้นสุดกำหนดการ</th>
                    <th>วันที่สร้างกำหนดการ</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($schedule = mysqli_fetch_assoc($result2)) { ?>
                    <?php $num1 = $num1 + 1; ?>
                    <tr>
                        <td><?php echo $num1; ?></td>
                        <td><?php echo $schedule['sender_name']; ?></td>
                        <td><?php echo $schedule['recipient_name']; ?></td>
                        <td><?php echo $schedule['sch_name']; ?></td>
                        <td><?php echo $schedule['time_sch']; ?></td>
                        <td><?php echo $schedule['end_time_sch']; ?></td>
                        <td><?php echo $schedule['time_create_schedule']; ?></td>
                        <td>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal1<?php echo $schedule['sch_id'] ?>">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal1<?php echo $schedule['sch_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">รายละเอียดกำหนดการ</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form class="row g-3">
                                                <div class="col-md-12">
                                                    <label for="" class="form-label">หัวข้อ</label>
                                                    <input type="text" class="form-control" name="sch_name" value="<?php echo $schedule['sch_name'] ?>" readonly>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="checkbox" name="sch_checkbox[]" value="Meet" <?php echo (strpos($schedule['sch_checkbox'], '1') !== false) ? 'checked' : ''; ?> onclick="return false;"> ประชุม &nbsp;
                                                    <input type="checkbox" name="sch_checkbox[]" value="Outside" <?php echo (strpos($schedule['sch_checkbox'], '2') !== false) ? 'checked' : ''; ?> onclick="return false;"> ไปต่างสถานที่ &nbsp;
                                                    <input type="checkbox" name="sch_checkbox[]" value="Important Day" <?php echo (strpos($schedule['sch_checkbox'], '3') !== false) ? 'checked' : ''; ?> onclick="return false;"> วันสำคัญ &nbsp;
                                                    <input type="checkbox" name="sch_checkbox[]" value="Exam" <?php echo (strpos($schedule['sch_checkbox'], '4') !== false) ? 'checked' : ''; ?> onclick="return false;"> สอบ &nbsp;
                                                    <input type="checkbox" name="sch_checkbox[]" value="Other" <?php echo (strpos($schedule['sch_checkbox'], '5') !== false) ? 'checked' : ''; ?> onclick="return false;"> อื่นๆ &nbsp;
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="sch_description" class="form-label">รายละเอียด</label><br>
                                                    <textarea class="form-control" name="sch_description" rows="5" readonly><?php echo $schedule['sch_description']; ?></textarea>
                                                </div>
                                                <div class="col-md-12 form-outline datetimepicker">
                                                    <label for="datetimepickerExample" class="form-label">วันเวลาเริ่มกำหนดการ</label>
                                                    <input type="datetime-local" class="form-control" name="time_sch" value="<?php echo date('Y-m-d\TH:i', strtotime($schedule['time_sch'])); ?>" readonly>
                                                </div>
                                                <div class="col-md-12 form-outline datetimepicker">
                                                    <label for="datetimepickerExample" class="form-label">วันเวลาสิ้นสุดกำหนดการ</label>
                                                    <input type="datetime-local" class="form-control" name="end_time_sch" value="<?php echo date('Y-m-d\TH:i', strtotime($schedule['end_time_sch'])); ?>" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="" class="form-label">วันเวลาที่สร้างกำหนดการ</label>
                                                    <input type="text" class="form-control" name="time_create_schedule" value="<?php echo $schedule['time_create_schedule'] ?>" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="" class="form-label">ผู้ส่ง</label>
                                                    <input type="text" class="form-control" name="schedule_friend_sender" value="<?php echo $schedule['schedule_friend_sender'] ?>" readonly>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="" class="form-label">ไฟล์</label>
                                                    <div class="file-container">
                                                        <?php
                                                        $fileQuery = "SELECT DISTINCT sch_id, schedule_friend_sender, sch_checkbox, schedule_file_name, sch_description, schedule_friend_recipient, sch_name, time_sch, time_create_schedule
                                                                        FROM schedule
                                                                        LEFT JOIN schedule_file ON schedule.sch_id = schedule_file.sch_file_id
                                                                        JOIN schedule_friend ON schedule.sch_id = schedule_friend.schedule_friend_id
                                                                        WHERE schedule_friend_recipient = $userId AND sch_file_id = " . $schedule['sch_id'];
                                                        $fileResult = mysqli_query($con, $fileQuery);

                                                        if ($fileResult && mysqli_num_rows($fileResult) > 0) {
                                                            while ($file = mysqli_fetch_assoc($fileResult)) {
                                                                $filePath = 'uploads_file_schedule/' . $file['schedule_file_name'];

                                                                if (file_exists($filePath)) {
                                                                    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

                                                                    if (in_array($fileExtension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
                                                                        // Only display PDF files here
                                                                        if ($fileExtension === 'pdf') {
                                                                            echo '<iframe src="' . $filePath . '" width="100%" height="500px"></iframe>';
                                                                            echo '_____________________________________________________';
                                                                            echo '_____________________________________________________';
                                                                        } else {
                                                                            // For other document types
                                                                            echo '<a href="' . $filePath . '" target="_blank">' . $file['schedule_file_name'] . '</a>';
                                                                        }
                                                                    } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                                        // For image types
                                                                        echo '<img src="' . $filePath . '" alt="Image" width="100%" />';
                                                                        echo '_____________________________________________________';
                                                                        echo '_____________________________________________________';
                                                                    } else {
                                                                        // For unsupported file types
                                                                        echo 'File type not supported: ' . $fileExtension;
                                                                    }
                                                                } else {
                                                                    echo 'File not found: ' . $filePath;
                                                                }
                                                            }
                                                        } else {
                                                            echo 'No files available';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-danger" onclick="confirmDelete(<?php echo $schedule['sch_id']; ?>)">ลบ</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <center>
            <a href="indexuser.php" class="btn btn-primary mt-5">หน้าแรก</a>
            <a href="javascript:history.back()" class="btn btn-outline-primary mt-5">ย้อนกลับ</a><br><br>
        </center>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function confirmDelete(schId) {
            Swal.fire({
                title: 'ต้องการที่จะลบหรือไม่?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ลบ!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Call the deleteSchedule function with the schedule ID
                    deleteSchedule(schId);
                }
            });
        }

        function deleteSchedule(schId) {
            // Send an AJAX request to delete_schedule.php with the schedule ID
            // Modify this URL as needed
            $.ajax({
                url: 'delete_sch_friend_add.php?sch_id=' + schId,
                type: 'GET',
                success: function(response) {
                    if (response === 'success') {
                        location.reload();
                    } else {
                        // If an error occurs, show an error message
                        Swal.fire('Error!', 'Unable to delete the schedule.', 'error');
                    }
                },
                error: function() {
                    // If there is a network error, show an error message
                    Swal.fire('Error!', 'Network error. Please try again.', 'error');
                }
            });
        }
    </script>
</body>

</html>