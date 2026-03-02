<?php
include('menu_Navbar_user.php');
?>

<?php
require('dbconnect.php');

$userId = $_SESSION["user_id"];

$thaiMonths = [
    'January' => 'มกราคม',
    'February' => 'กุมภาพันธ์',
    'March' => 'มีนาคม',
    'April' => 'เมษายน',
    'May' => 'พฤษภาคม',
    'June' => 'มิถุนายน',
    'July' => 'กรกฎาคม',
    'August' => 'สิงหาคม',
    'September' => 'กันยายน',
    'October' => 'ตุลาคม',
    'November' => 'พฤศจิกายน',
    'December' => 'ธันวาคม'
];

// Get the selected month and year from URL parameter
$selectedMonthYear = isset($_GET['selectedMonthYear']) ? mysqli_real_escape_string($con, $_GET['selectedMonthYear']) : date('m-Y');

// Extract month and year from the selected value
list($selectedMonth, $selectedYear) = explode('-', $selectedMonthYear);

// Query to count the occurrences of value 1 in sch_checkbox for the selected month and year
$sqlCount = "SELECT COUNT(*) AS meeting_count FROM schedule WHERE sch_id_sender = $userId AND (sch_checkbox = 1 OR sch_checkbox = '1') AND MONTH(time_sch) = $selectedMonth AND YEAR(time_sch) = $selectedYear";
$resultCount = mysqli_query($con, $sqlCount);
$rowCount = mysqli_fetch_assoc($resultCount);

// Get the count of meetings
$meetingCount = $rowCount['meeting_count'];

// Query to count the occurrences of value 2 in sch_checkbox for the selected month and year
$sqlCount2 = "SELECT COUNT(*) AS outside_count FROM schedule WHERE sch_id_sender = $userId AND (sch_checkbox = 2 OR FIND_IN_SET('2', sch_checkbox)) AND MONTH(time_sch) = $selectedMonth AND YEAR(time_sch) = $selectedYear";
$resultCount2 = mysqli_query($con, $sqlCount2);
$rowCount2 = mysqli_fetch_assoc($resultCount2);

// Get the count of meetings for value 2 and assign it to $outside
$outside = $rowCount2['outside_count'];

// Query to count the occurrences of value 3 in sch_checkbox for the selected month and year
$sqlCount3 = "SELECT COUNT(*) AS important_day_count FROM schedule WHERE sch_id_sender = $userId AND (sch_checkbox = 3 OR FIND_IN_SET('3', sch_checkbox)) AND MONTH(time_sch) = $selectedMonth AND YEAR(time_sch) = $selectedYear";
$resultCount3 = mysqli_query($con, $sqlCount3);
$rowCount3 = mysqli_fetch_assoc($resultCount3);

// Get the count of meetings for value 3 and assign it to $important
$important = $rowCount3['important_day_count'];

// Query to count the occurrences of value 4 in sch_checkbox for the selected month and year
$sqlCount4 = "SELECT COUNT(*) AS exam_count FROM schedule WHERE sch_id_sender = $userId AND (sch_checkbox = 4 OR FIND_IN_SET('4', sch_checkbox)) AND MONTH(time_sch) = $selectedMonth AND YEAR(time_sch) = $selectedYear";
$resultCount4 = mysqli_query($con, $sqlCount4);
$rowCount4 = mysqli_fetch_assoc($resultCount4);

// Get the count of meetings for value 4 and assign it to $exam
$exam = $rowCount4['exam_count'];

// Query to count the occurrences of value 5 in sch_checkbox for the selected month and year
$sqlCount5 = "SELECT COUNT(*) AS other_count FROM schedule WHERE sch_id_sender = $userId AND (sch_checkbox = 5 OR FIND_IN_SET('5', sch_checkbox)) AND MONTH(time_sch) = $selectedMonth AND YEAR(time_sch) = $selectedYear";
$resultCount5 = mysqli_query($con, $sqlCount5);
$rowCount5 = mysqli_fetch_assoc($resultCount5);

// Get the count of meetings for value 5 and assign it to $other
$other = $rowCount5['other_count'];

// Query to get distinct months from the schedule table
$sqlDistinctMonths = "SELECT DISTINCT MONTH(time_sch) AS distinct_month, YEAR(time_sch) AS distinct_year 
                      FROM schedule 
                      WHERE sch_id_sender = $userId
                      ORDER BY distinct_year ASC, distinct_month ASC";
$resultDistinctMonths = mysqli_query($con, $sqlDistinctMonths);

// Create an array to store the available months and years
$availableMonthsYears = array();
while ($row = mysqli_fetch_assoc($resultDistinctMonths)) {
    $year = $row['distinct_year'];
    $month = $row['distinct_month'];
    $availableMonthsYears["$month-$year"] = $thaiMonths[date('F', mktime(0, 0, 0, $month, 1, $year))] . " $year";
}

// Define your data points for the column chart with the updated label
$dataPointsColumn = array( 
    array("y" => $meetingCount, "label" => "ประชุม" ),
    array("y" => $outside, "label" => "ไปต่างสถานที่" ),
    array("y" => $important, "label" => "วันสำคัญ" ),
    array("y" => $exam, "label" => "สอบ" ),
    array("y" => $other, "label" => "อื่นๆ" ),
);

// Define your data points for the pie chart
$dataPointsPie = array(
    array("label"=> "ประชุม", "y"=> $meetingCount),
    array("label"=> "ไปต่างสถานที่", "y"=> $outside),
    array("label"=> "วันสำคัญ", "y"=> $important),
    array("label"=> "สอบ", "y"=> $exam),
    array("label"=> "อื่นๆ", "y"=> $other),
);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Page Title</title>
    <style>
        .nav-tabs {
            display: flex;
            justify-content: center;
        }

        .font-thai {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>
<body>
    <div style="height: 150px;"></div>
    <div class="container">
        <div class="row">
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="nav-item font-thai"><a class="nav-link active" href="#tab1" data-bs-toggle="tab">แบบแผนภูมิแท่ง</a></li>
                    <li class="nav-item font-thai"><a class="nav-link" href="#tab2" data-bs-toggle="tab">แบบวงกลม</a></li>
                </ul>
                <div class="tab-content font-thai">
                    <div class="tab-pane active" id="tab1">
                        <br>
                        <br>
                        <select id="monthYearDropdown" class="form-select col-4" onchange="onMonthYearSelect(this)">
                            <?php
                            foreach ($availableMonthsYears as $monthYear => $monthYearLabel) {
                                echo "<option value='$monthYear'";
                                if ($selectedMonthYear == $monthYear) {
                                    echo " selected";
                                }
                                echo ">$monthYearLabel</option>";
                            }
                            ?>
                        </select>
                        <br>
                        <br>
                        <!DOCTYPE HTML>
                        <html>
                        <head>
                        <script>
                        function onMonthYearSelect(dropdown) {
                            var selectedMonthYear = dropdown.value;
                            window.location.href = '?selectedMonthYear=' + selectedMonthYear;
                        }

                        window.onload = function() {
                            var chart = new CanvasJS.Chart("chartContainer", {
                                animationEnabled: true,
                                theme: "light2",
                                title:{
                                    text: "ประเภทของกำหนดการ/เดือน (แบบแผนภูมิแท่ง)",
                                    fontFamily: 'Kanit',
                                },
                                axisY: {
                                    title: "จำนวนประเภทที่เลือก",
                                    fontFamily: 'Kanit',
                                },
                                data: [{
                                    type: "column",
                                    yValueFormatString: "#,##0.## ครั้ง",
                                    indexLabel: "{y}", // Display data values inside the columns
                                    indexLabelFontColor: "black", // Customize font color
                                    dataPoints: <?php echo json_encode($dataPointsColumn, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                            chart.render();
                        }
                        </script>
                        </head>
                        <body>
                        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                        <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
                        </body>
                        </html> 
                    </div>

                    <div class="tab-pane" id="tab2">
                        <br>
                        <br>
                        <select id="monthYearDropdown2" class="form-select col-4" onchange="onMonthYearSelect(this)">
                            <?php
                            foreach ($availableMonthsYears as $monthYear => $monthYearLabel) {
                                echo "<option value='$monthYear'";
                                if ($selectedMonthYear == $monthYear) {
                                    echo " selected";
                                }
                                echo ">$monthYearLabel</option>";
                            }
                            ?>
                        </select>
                        <br>
                        <br>
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <div id="chartContainer2" style="height: 370px; width: 50%;"></div>

                            <script>
                            // สร้างแผนภูมิวงกลมที่นี่
                            var chart2 = new CanvasJS.Chart("chartContainer2", {
                                animationEnabled: true,
                                exportEnabled: true,
                                title: {
                                    text: "ประเภทของกำหนดการ/เดือน",
                                    fontFamily: 'Kanit',
                                },
                                subtitles: [{
                                    text: "(แบบแผนภูมิวงกลม)",
                                    fontFamily: 'Kanit',
                                }],
                                data: [{
                                    type: "pie",
                                    showInLegend: "true",
                                    legendText: "{label}",
                                    indexLabelFontSize: 16,
                                    indexLabel: "{label} - #percent%",
                                    dataPoints: <?php echo json_encode($dataPointsPie, JSON_NUMERIC_CHECK); ?>
                                }]
                            });
                            chart2.render();
                            </script>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function () {
            var hash = window.location.hash;
            hash && $('ul.nav a[href="' + hash + '"]').tab('show');

            $('.nav-tabs a').click(function (e) {
                $(this).tab('show');
                var scrollmem = $('body').scrollTop();
                window.location.hash = this.hash;
                $('html,body').scrollTop(scrollmem);
            });
        });
    </script>
</body>
</html>