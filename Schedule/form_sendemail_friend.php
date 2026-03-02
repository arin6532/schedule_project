<?php
include('menu_Navbar_user.php');
require('dbconnect.php');
$userId = $_SESSION["user_id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div style="height: 150px;"></div>
    <div class="sendmail">
        <form id="myForm" class="card-sendmail" enctype="multipart/form-data">
            <div class="msg"></div>

            <h2>กำหนดการเร่งด่วน</h2>

            <div class="form-control-sendmail">
                <?php
                $query2 = "SELECT name FROM userlogin WHERE user_id = '$userId'";
                $result2 = mysqli_query($con, $query2);
                
                if ($row2 = mysqli_fetch_assoc($result2)) {
                    $schName = $row2['name'];
                } else {
                    $schName = ""; // Set a default value if no result is found
                }
                ?>
                <p style="color: black; font-size: 18px; font-weight: 450;">ผู้ส่ง</p>
                <input type="text" id="name" class="txt-sendmail" value="<?php echo $schName; ?>" readonly>
            </div>

            <div class="form-control-sendmail">
                <p style="color: black; font-size: 18px; font-weight: 450;">ผู้รับ</p>
                <!-- รายชื่อเพื่อน -->
                <div class="container">
                    <div class="row">
                        <h4 id="friend-list"></h4>
                        <div id="friend-list-selected" class="form-control " onclick="toggleFriendList()">*รายชื่อเพื่อน*</div>
                        <div class="friend-list">
                            <?php
                            // Retrieve friend_user data with friend_type = 2 based on id_recipient being the current user
                            $query = "SELECT u.user_id as friend_id, u.name as friend_name, u.email as friend_email
                                    FROM friend_user f
                                    JOIN userlogin u ON f.id_sender = u.user_id
                                    WHERE f.id_recipient = '$userId' AND f.friend_type = 2
                                    UNION ALL
                                    SELECT u.user_id as friend_id, u.name as friend_name, u.email as friend_email
                                    FROM friend_user f
                                    JOIN userlogin u ON f.id_recipient = u.user_id
                                    WHERE f.id_sender = '$userId' AND f.friend_type = 2";

                            $result = mysqli_query($con, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $friendId = $row["friend_id"];
                                $friendName = $row["friend_name"];
                                $friendEmail = $row["friend_email"];
                                ?>
                                <div class="friend-item">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input friend-option"
                                            value="<?php echo $friendEmail; ?>" id="email" name="friendEmail[]" onclick="updateSelectedFriends()">
                                        <input type="hidden" name="friendId[]" value="<?php echo $friendId; ?>">
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
            <div class="form-control-sendmail">
                <p style="color: black; font-size: 18px; font-weight: 450;">หัวข้อ</p>
                <input type="text" id="header" class="txt-sendmail" placeholder="insert header">
            </div>

            <div class="form-control-sendmail">
                <p style="color: black; font-size: 18px; font-weight: 450;">รายละเอียด</p>
                <textarea id="detail" class="txt-sendmail txtarea-sendmail" placeholder="insert detail"></textarea><br><br>
            </div>

            <div class="form-control-sendmail">
                <p style="color: black; font-size: 18px; font-weight: 450;">วันและเวลาเริ่มกำหนดการ</p>
                <input type="datetime-local" id="date" class="txt-sendmail">
            </div>
            <div class="form-control-sendmail">
                <p style="color: black; font-size: 18px; font-weight: 450;">วันและเวลาสิ้นสุดกำหนดการ</p>
                <input type="datetime-local" id="end_date" class="txt-sendmail">
            </div>

            <div class="form-control-sendmail">
                <p style="color: black; font-size: 18px; font-weight: 450;">เลือกไฟล์</p>
                <input type="file" id="file" name="file[]" multiple>
            </div>

            <br><button type="button" onclick="sendEmail()" class="btn-submit-sendmail">ส่ง</button> <br>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function toggleFriendList() {
            var friendList = document.querySelector(".friend-list");
            friendList.style.display = friendList.style.display === "none" ? "block" : "none";
        }

        function updateSelectedFriends() {
            var friendList = document.querySelector(".friend-list");
            var friendOptions = document.querySelectorAll(".friend-option:checked");

            var selectedFriends = [];
            for (var i = 0; i < friendOptions.length; i++) {
                selectedFriends.push(friendOptions[i].value);
            }

            var selectedFriendsDisplay = selectedFriends.length > 0 ? selectedFriends.join(", ") : "*Friend List*";
            document.querySelector("#friend-list-selected").textContent = selectedFriendsDisplay;

            friendList.style.display = "none";
        }

        function sendEmail() {
            var name = $("#name");
            var date = $("#date");
            var end_date = $("#end_date");
            var header = $("#header");
            var detail = $("#detail");
            var files = $("#file")[0].files; // Get an array of selected files

            var friendOptions = document.querySelectorAll(".friend-option:checked");
            if (friendOptions.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please select at least one friend to send the email to.'
                });
                return;
            }

            var formData = new FormData();
            formData.append("name", name.val());
            formData.append("date", date.val());
            formData.append("end_date", end_date.val());
            formData.append("header", header.val());
            formData.append("detail", detail.val());


            // Add selected friends' emails and friendIds to the form data
            for (var i = 0; i < friendOptions.length; i++) {
                formData.append("friendEmail[]", friendOptions[i].value);
                // Get the friendId from the hidden input
                var friendId = friendOptions[i].parentNode.querySelector("input[type='hidden'][name='friendId[]']").value;
                formData.append("friendId[]", friendId);
            }

            // Add selected files to the form data
            for (var j = 0; j < files.length; j++) {
                formData.append("file[]", files[j]);
            }


            $.ajax({
                url: 'sendEmail.php',
                method: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.response,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // เพิ่มการกระทำเพิ่มเติมหลังจากคลิก "OK" ตรงนี้
                                // เช่น ล้างค่าฟอร์มหรือทำอย่างอื่นตามที่คุณต้องการ
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.response,
                        });
                    }
                },
                error: function (xhr, status, error) {
                    // จัดการข้อผิดพลาดของการร้องขอ AJAX ที่อาจเกิดขึ้น
                    console.log("AJAX Request Error: " + error);
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: 'บันทึกเรียบร้อย',
                    });
                }
            });

    }
    </script>
    <div style="height: 150px;"></div>
</body>
</html>

<style>
        .sendmail {
            width: 100%;
            font-family: 'Kanit', sans-serif;
        }

        .card-sendmail {
            max-width: 850px;
            background: #FFF;
            padding: 30px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-control-sendmail {
            width: 80%; /* เปลี่ยนขนาดเป็น 80% เพื่อให้แบบฟอร์มกว้างขึ้น */
            margin: 10px 0;
        }

        .txt-sendmail {
            font-size: 1rem;
            padding: 10px;
            background: #F7F7F7;
            border: 1px solid #E5E5E5;
            border-radius: 5px;
            width: 100%;
        }

        .btn-submit-sendmail {
            width: 80%; /* เปลี่ยนขนาดเป็น 80% เพื่อให้ปุ่มกว้างขึ้น */
            font-size: 1rem;
            padding: 10px 0;
            border: none;
            background: #333;
            color: #FFF;
            cursor: pointer;
            border-radius: 5px;
        }

        .txtarea-sendmail {
            height: 100px;
        }

        /* CSS สำหรับแสดงรายชื่อเพื่อน */
        .friend-list {
            display: none;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #fff;
            max-height: 200px;
            overflow-y: auto;
        }

        .friend-item {
            margin: 5px 0;
        }

        /* CSS สำหรับอีเมลล์ของเพื่อน */
        .selected-friend-email {
            margin-top: 10px;
            font-weight: 600;
        }
    </style>