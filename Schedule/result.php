<?php include('menu_Navbar_user.php'); ?>

<?php
// Include your database connection setup
require('dbconnect.php');

// Get the user ID of the currently logged-in user
$userId = $_SESSION["user_id"]; // Assuming you have a session variable for the current user's ID

// Initialize the searchKeyword variable
$searchKeyword = "";

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchKeyword = $_POST["search_keyword"];
}

// Query the database to get schedule data
$sql = "SELECT sch_id_sender, sch_name, time_sch, time_create_schedule, end_time_sch FROM schedule WHERE sch_id_sender = '$userId'";
$result = mysqli_query($con, $sql);
$num = 0;

// Query to get schedule data with friends
$sql1 = "SELECT DISTINCT sch_id, sch_id_sender, sch_name, sch_checkbox, sch_description, time_sch, time_create_schedule, end_time_sch, userlogin.name, GROUP_CONCAT(schedule_file.schedule_file_name SEPARATOR ', ') AS file_names
         FROM schedule
         LEFT JOIN schedule_file ON schedule.sch_id = schedule_file.sch_file_id
         LEFT JOIN schedule_friend ON schedule.sch_id = schedule_friend.schedule_friend_id
         JOIN userlogin ON schedule.sch_id_sender = userlogin.user_id
         WHERE schedule.sch_id_sender = $userId
         AND schedule.sch_name LIKE '%$searchKeyword%'  -- Add this line to filter by schedule name
         GROUP BY schedule.sch_id
         ORDER BY time_create_schedule ASC";
$result1 = mysqli_query($con, $sql1);

// Function to check if a file exists and return its type
function getFileType($filePath)
{
    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
    if (in_array($fileExtension, ['pdf', 'doc', 'docx', 'xls', 'xlsx'])) {
        return 'pdf';
    }
    return 'image';
}

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
    </style>
</head>

<body>
    <div style="height: 150px;"></div>
    <div class="card p-3 container font-thai">
        <h2 class="text-center mt-5">กำหนดการที่คุณสร้าง</h2>
        <table class="table mt-4">
            <form class="form-group" action="" method="POST">
                <tbody>
                    <tr>
                        <td style="width: 100%;">
                            <input type="text" name="search_keyword" placeholder="ค้นหาจากหัวข้อ" class="form-control" required value="<?php echo $searchKeyword; ?>">
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
                    <th>ผู้สร้าง</th>
                    <th>หัวข้อ</th>
                    <th>วันเวลาเริ่มกำหนดการ</th>
                    <th>วันเวลาสิ้นสุดกำหนดการ</th>
                    <th>วันที่สร้างกำหนดการ</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($schedule = mysqli_fetch_assoc($result1)) { ?>
                    <?php $num = $num + 1; ?>
                    <tr>
                        <td><?php echo $num; ?></td>
                        <td><?php echo $schedule['name']; ?></td>
                        <td><?php echo $schedule['sch_name']; ?></td>
                        <td><?php echo $schedule['time_sch']; ?></td>
                        <td><?php echo $schedule['end_time_sch']; ?></td>
                        <td><?php echo $schedule['time_create_schedule']; ?></td>
                        <td>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $schedule['sch_id'] ?>">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal<?php echo $schedule['sch_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5 " id="exampleModalLabel">รายละเอียดกำหนดการ</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <!-- Check box สำหรับเอาไว้เก็บประเภทข้อมูลเพื่อเอาไปสรุป -->
                                        <div class="modal-body">
                                            <form class="row g-3" action="update_schedule_you_create.php" method="POST" enctype="multipart/form-data">
                                                <div class="col-md-12">
                                                    <label for="" class="form-label">หัวข้อ</label>
                                                    <!-- แสดงชื่อรายการใน input -->
                                                    <input type="text" class="form-control" id="" name="sch_name" value="<?php echo $schedule['sch_name'] ?>" required>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="hidden" name="sch_id" value="<?php echo $schedule['sch_id']; ?>">
                                                    <input type="checkbox" name="sch_checkbox[]" value="1" <?php echo (strpos($schedule['sch_checkbox'], '1') !== false) ? 'checked' : ''; ?>> ประชุม &nbsp;
                                                    <input type="checkbox" name="sch_checkbox[]" value="2" <?php echo (strpos($schedule['sch_checkbox'], '2') !== false) ? 'checked' : ''; ?>> ไปต่างสถานที่ &nbsp;
                                                    <input type="checkbox" name="sch_checkbox[]" value="3" <?php echo (strpos($schedule['sch_checkbox'], '3') !== false) ? 'checked' : ''; ?>> วันสำคัญ &nbsp;
                                                    <input type="checkbox" name="sch_checkbox[]" value="4" <?php echo (strpos($schedule['sch_checkbox'], '4') !== false) ? 'checked' : ''; ?>> สอบ &nbsp;
                                                    <input type="checkbox" name="sch_checkbox[]" value="5" <?php echo (strpos($schedule['sch_checkbox'], '5') !== false) ? 'checked' : ''; ?>> อื่นๆ &nbsp;
                                                </div>
                                                <!-- Description เอาไว้อธิบาย -->
                                                <div class="col-md-12">
                                                    <label for="sch_description" class="form-label">รายละเอียด</label><br>
                                                    <textarea class="form-control" name="sch_description" rows="5"><?php echo $schedule['sch_description']; ?></textarea>
                                                </div>
                                                <!-- เลือกวัน -->
                                                <div class="col-md-12 form-outline datetimepicker">
                                                    <input type="datetime-local" class="form-control" name="time_sch" value="<?php echo date('Y-m-d\TH:i', strtotime($schedule['time_sch'])); ?>" id="datetimepickerExample" required>
                                                    <label for="datetimepickerExample" class="form-label">วันเวลาเริ่มกำหนดการ</label>
                                                </div>
                                                <div class="col-md-12 form-outline datetimepicker">
                                                    <input type="datetime-local" class="form-control" name="end_time_sch" value="<?php echo date('Y-m-d\TH:i', strtotime($schedule['end_time_sch'])); ?>" id="datetimepickerExample" required>
                                                    <label for="datetimepickerExample" class="form-label">วันเวลาสิ้นสุดกำหนดการ</label>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="" class="form-label">วันเวลาที่สร้างกำหนดการ</label>
                                                    <!-- แสดงชื่อรายการใน input -->
                                                    <input type="text" class="form-control" id="" name="time_create_schedule" value="<?php echo $schedule['time_create_schedule'] ?>" readonly>
                                                </div>
                                                <div class="col-md-12">
                                                    <label for="" class="form-label">ไฟล์</label>
                                                    <?php
                                                    $fileQuery = "SELECT DISTINCT schedule_file_name FROM schedule_file WHERE sch_file_id = " . $schedule['sch_id'];
                                                    $fileResult = mysqli_query($con, $fileQuery);

                                                    if (mysqli_num_rows($fileResult) > 0) {
                                                        while ($file = mysqli_fetch_assoc($fileResult)) {
                                                            $filePath = 'uploads_file_schedule/' . $file['schedule_file_name'];

                                                            if (file_exists($filePath)) {
                                                                $fileType = getFileType($filePath);

                                                                if ($fileType === 'pdf') {
                                                                    echo '<iframe src="' . $filePath . '" width="100%" height="500px"></iframe>';
                                                                    echo '_____________________________________________________';
                                                                    echo '_____________________________________________________';
                                                                } else {
                                                                    echo '<img src="' . $filePath . '" alt="Image" width="100%" />';
                                                                    echo '_____________________________________________________';
                                                                    echo '_____________________________________________________';
                                                                }
                                                            } else {
                                                                echo 'No file available';
                                                            }
                                                        }
                                                    } else {
                                                        echo 'No files available';
                                                    }
                                                    ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                    <button type="submit" class="btn btn-primary">บันทึก</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-danger" onclick="confirmDelete('<?php echo $schedule['sch_id']; ?>')">ลบ</button>
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
    <div style="height: 150px;"></div>

    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(scheduleId) {
            Swal.fire({
                title: 'ต้องการที่จะลบหรือไม่?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ลบ!',
                cancelButtonText: 'ยกเลิก',
            }).then((result) => {
                if (result.isConfirmed) {
                    // User confirmed, proceed with deletion
                    window.location.href = 'delete_sch_you_create.php?sch_id=' + scheduleId;
                }
            });
        }
    </script>
</body>

</html>