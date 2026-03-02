<style>
    /* แก้ไขสีแถบวันที่ของ FullCalendar เป็นสีน้ำเงิน */
    .fc-event.result1 {
        background-color: #3366FF;
    }

    /* แก้ไขสีแถบวันที่ของ FullCalendar เป็นสีแดง */
    .fc-event.result2 {
        background-color: #CC3333;
    }
</style>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'dbconnect.php';

$userId = $_SESSION["user_id"];
$data = array();

// ตรวจสอบว่าตัวแปร $userId มีค่าหรือไม่
if (isset($userId)) {
    // Include your database connection setup
    require('dbconnect.php');
    
    $sql1 = "SELECT sch_id_sender, sch_name, time_sch, time_create_schedule, sch_description, end_time_sch FROM schedule WHERE sch_id_sender = '$userId'";
    $result1 = mysqli_query($con, $sql1);

    $sql2 = "SELECT urgent_id_sender, urgent_title, urgent_time_sch, urgent_end_date, urgent_time_create, urgent_description FROM urgent_schedule WHERE urgent_id_sender = '$userId'";
    $result2 = mysqli_query($con, $sql2);
    
    while ($row = mysqli_fetch_assoc($result1)) {
        $data[] = array(
            'title' => $row["sch_name"].' '.'ระยะเวลา'.' '.date("H:i", strtotime($row["time_sch"])). ' - ' .date("H:i", strtotime($row["end_time_sch"])),
            'eventTitle2' => $row["sch_name"],
            'start' => $row["time_sch"],
            'end' => $row["end_time_sch"],
            'description' => $row["sch_description"],
            'create_time' => $row["time_create_schedule"],
            'className' => 'result1'
        );
    }
    
    while ($row = mysqli_fetch_assoc($result2)) {
        $data[] = array(
            'title' => $row["urgent_title"].' '.'ระยะเวลา'.' '.date("H:i", strtotime($row["urgent_time_sch"])). ' - ' .date("H:i", strtotime($row["urgent_end_date"])),
            'eventTitle2' => $row["urgent_title"],
            'start' => $row["urgent_time_sch"],
            'end' => $row["urgent_end_date"],
            'description' => $row["urgent_description"],
            'create_time' => $row["urgent_time_create"],
            'className' => 'result2'
        );
    }
}
?>
<style>
    .font-thai {
        font-family: 'Kanit', sans-serif;
    }
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include your CSS and JS dependencies here -->
    <link href="fullcalendar/main.css" rel="stylesheet" />
    <style>
        /* Set the maximum width of the description cell and allow text to wrap */
        #eventDescription {
            max-width: 300px; /* Adjust the maximum width as needed */
            word-wrap: break-word;
        }
        #eventTitle {
            max-width: 300px; /* Adjust the maximum width as needed */
            word-wrap: break-word;
        }
    </style>
    <script src="fullcalendar/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.2/dist/js/bootstrap.min.js"></script>
    <!-- Include Moment.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</head>
<body>
    <!-- Event Details Modal -->
    <div class="modal fade font-thai" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">รายละเอียดกำหนดการ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>หัวข้อ</th>
                                <td id="eventTitle2"></td>
                            </tr>
                            <tr>
                                <th>วันเวลาเริ่มกำหนดการ</th>
                                <td id="eventStart"></td>
                            </tr>
                            <tr>
                                <th>วันเวลาสิ้นสุดกำหนดการ</th>
                                <td id="eventEnd"></td>
                            </tr>
                            <tr>
                                <th>รายละเอียดกำหนดการ</th>
                                <td id="eventDescription"></td>
                            </tr>
                            <tr>
                                <th>วันที่สร้างกำหนดการ</th>
                                <td id="eventCreateTime"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar -->
    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                displayEventTime: false,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,listWeek'
                },
                events: <?php echo json_encode($data); ?>,
                eventClick: function(info) {
                    // Display event details in the modal with Thai date format
                    $('#eventModal').modal('show');
                    $('#eventTitle2').text(info.event.extendedProps.eventTitle2); // แสดงใน modal
                    $('#eventStart').text(moment(info.event.start).format('LL LT')); // Thai date and time format
                    $('#eventEnd').text(moment(info.event.end).format('LL LT')); // เพิ่มบรรทัดนี้เพื่อแสดงวันเวลาสิ้นสุด
                    $('#eventDescription').text(info.event.extendedProps.description);
                    $('#eventCheckbox').text(info.event.extendedProps.checkbox);
                    $('#eventCreateTime').text(info.event.extendedProps.create_time);
                }
            });

            calendar.render();
        });
    </script>
</body>
</html>