<?php include('menu_Navbar_user.php');?>

<!-- Css สำหรับซ่อน Frien List -->
<style>
        body {
            font-family: Arial, sans-serif;
        }

        .friend-list {
            display: none;
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 5px;
        }

        .friend-item {
            margin-bottom: 5px;
        }
        .card-form-schedule {
            padding: 30px;
            margin: auto;
            margin-top: 150px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .font-thai{
            font-family: 'Kanit', sans-serif;
        }
        
    </style>
    <!-- สคริปสำหรับการแสดงรายชื่อเพื่อน เมื่อกด Frien List -->
    <script>
        function toggleFriendList() {
            var friendList = document.querySelector(".friend-list");
            friendList.style.display = friendList.style.display === "none" ? "block" : "none";
        }

        function updateSelectedFriends() {
            var friendList = document.querySelector(".friend-list");
            var friendOptions = document.querySelectorAll(".friend-option");

            var selectedFriends = [];
            for (var i = 0; i < friendOptions.length; i++) {
                if (friendOptions[i].checked) {
                    selectedFriends.push(friendOptions[i].value);
                }
            }

            var selectedFriendsDisplay = selectedFriends.length > 0 ? selectedFriends.join(", ") : "*Friend List*";
            document.querySelector("#friend-list-selected").textContent = selectedFriendsDisplay;

            friendList.style.display = "none";
        }
    </script>
<body>

    <!-- สร้างการ์ดเพื่อคลุมฟอร์ม -->
    <div class="card-container">
        <div class="card-form-schedule">
            <!-- วางฟอร์ม -->
            <form class="row g-3" action="update_schedule_user.php" method="POST" enctype="multipart/form-data">
                <div class="col-md-12" style="font-size: 35px; text-align: center; font-family: 'Noto Sans Thai', sans-serif;">สร้างกำหนดการ</div>
                <div class="col-md-12">
                    <label for="" class="form-label font-thai">หัวข้อ</label>
                    <input type="text" class="form-control" id="" name="sch_name" value="" required>
                </div>
                <!-- Check box สำหรับเอาไว้เก็บประเภทข้อมูลเพื่อเอาไปสรุป -->
                <div class="col-md-12 font-thai">
                    <input type="checkbox" name="sch_checkbox[]" value="1"> ประชุม &nbsp;
                    <input type="checkbox" name="sch_checkbox[]" value="2"> ไปต่างสถานที่ &nbsp;
                    <input type="checkbox" name="sch_checkbox[]" value="3"> วันสำคัญ &nbsp;
                    <input type="checkbox" name="sch_checkbox[]" value="4"> สอบ &nbsp;
                    <input type="checkbox" name="sch_checkbox[]" value="5"> อื่นๆ &nbsp;
                </div>
                <!-- Description เอาไว้อธิบาย -->
                <div class="col-md-12 font-thai">
                    <label for="sch_description" class="form-label">รายละเอียด</label><br>
                        <textarea class="form-control" name="sch_description" rows="5"></textarea>
                </div>
                <!-- เลือก Single or Group -->     
                <div class="question-answer col-md-12 font-thai">
                    <label for="sch_alert_type" class="form-label">การแจ้งเตือน :</label><br>
                    <label><input type="radio" value="1" name="Dine" onclick="showFriendOptions('single')" required> ส่วนบุคคล</label>
                    <label><input type="radio" value="2" name="Dine" onclick="showFriendOptions('group')" required> กลุ่ม</label>
                </div>
                <!-- รายชื่อเพื่อน -->

                <!-- รายชื่อเพื่อน -->
                <div class="container font-thai">
                    <div class="row">
                        <h4 id="friend-list"></h4>
                        <div class="col-md-12">
                            <div id="friend-list-selected" class="form-control" onclick="toggleFriendList()">*รายชื่อเพื่อน*</div>
                            <div class="friend-list">
                                <?php
                                // Include your database connection setup
                                require('dbconnect.php');

                                // Get the user ID of the currently logged-in user
                                $userId = $_SESSION["user_id"]; // Assuming you have a session variable for the current user's ID

                                // Retrieve friend_user data with friend_type = 2 based on id_recipient being the current user
                                $query = "SELECT u.user_id as friend_id, u.name as friend_name
                                        FROM friend_user f
                                        JOIN userlogin u ON f.id_sender = u.user_id
                                        WHERE f.id_recipient = '$userId' AND f.friend_type = 2
                                        UNION ALL
                                        SELECT u.user_id as friend_id, u.name as friend_name
                                        FROM friend_user f
                                        JOIN userlogin u ON f.id_recipient = u.user_id
                                        WHERE f.id_sender = '$userId' AND f.friend_type = 2";

                                $result = mysqli_query($con, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $friendId = $row["friend_id"];
                                    $friendName = $row["friend_name"];
                                ?>
                                <div class="friend-item">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input friend-option" value="<?php echo $friendId; ?>" name="friendId[]" onclick="updateSelectedFriends()">
                                        <label class="form-check-label" for="<?php echo $friendName; ?>"><?php echo $friendName; ?></label>
                                    </div>
                                </div>
                                <?php
                                }
                                mysqli_close($con);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- เลือกวัน -->
                <div class="col-12 form-outline datetimepicker font-thai">
                    <input type="datetime-local" class="form-control" name="time_sch" value="" id="datetimepickerExample" required>
                    <label for="datetimepickerExample" class="form-label">วันเวลาเริ่มกำหนดการ</label>
                </div>
                <div class="col-12 form-outline datetimepicker font-thai">
                    <input type="datetime-local" class="form-control" name="end_time_sch" value="" id="datetimepickerExample" required>
                    <label for="datetimepickerExample" class="form-label">วันเวลาสิ้นสุดกำหนดการ</label>
                </div>
                <!-- เพิ่ม input field สำหรับอัพโหลดไฟล์ -->
                <div class="col-md-6 font-thai">
                    <label for="schedule_file" class="form-label">เลือกไฟล์</label>
                    <input class="form-control" type="file" id="schedule_file" name="schedule_file[]" multiple />
                </div>
                
                <div class="col-12 font-thai">
                    <center><button type="submit" class="btn btn-primary">บันทึก</button>
                            <button type="reset" class="btn btn-danger">รีเซ็ต</button>
                    </center>
                </div>
                <!-- สคริปสำหรับการแสดง Frien List เมื่อกด Group -->
                <script>
                    function showFriendOptions(groupType) {
                        var friendListLabel = document.getElementById('friend-list');
                        var friendListOptions = document.getElementById('friend-list-selected');

                        if (groupType === 'group') {
                            friendListLabel.style.display = 'block';
                            friendListOptions.style.display = 'block';
                        } else {
                            friendListLabel.style.display = 'none';
                            friendListOptions.style.display = 'none';
                        }
                    }

                    function toggleFriendList() {
                        var friendList = document.querySelector(".friend-list");
                        friendList.style.display = friendList.style.display === "none" ? "block" : "none";
                    }

                    // ซ่อนรายการเพื่อนเมื่อหน้าเว็บโหลด
                    showFriendOptions('single');
                </script>
            </form>
        </div>
    </div>
    <div style="height: 350px;"></div>
        <!-- Include Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>