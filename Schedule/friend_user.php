<?php include('menu_Navbar_user.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Friend List</title>
    <style>
        /* Add CSS styles for circular images */
        .circle-image {
            border-radius: 50%;
            width: 50px;
            height: 50px;
        }

        .font-thai {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>
<body>
    <div style="height: 150px;"></div>
    <div class="card p-3 container font-thai">
        <h2 class="text-center mt-5">รายชื่อเพื่อน</h2>
        <table class="table mt-4">
            <form class="form-group" action="" method="POST">
                <tbody>
                    <tr>
                        <td style="width: 65%;">
                            <input type="text" name="search_query" placeholder="Search ID or Name" class="form-control" required>
                        </td>
                        <td>
                            <input type="submit" value="ค้นหา" class="btn btn-warning">
                        </td>
                        <td></td>
                        <td style="text-align: right;">
                            <a href="add_friend.php" class="btn btn-outline-primary">เพิ่มเพื่อน</a>
                            <a href="request_friend.php" class="btn btn-outline-warning">คำขอเป็นเพื่อน</a>
                        </td>
                    </tr>
                </tbody>
            </form>
        </table>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID</th>
                    <th>ชื่อ</th>
                    <th>Email</th>
                    <th>ภาพโปรไฟล์</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include your database connection setup
                require('dbconnect.php');

                // Get the user ID of the currently logged-in user
                $userId = $_SESSION["user_id"]; // Assuming you have a session variable for the current user's ID

                // Check if the search query is set and not empty
                if (isset($_POST["search_query"]) && !empty($_POST["search_query"])) {
                    $searchQuery = mysqli_real_escape_string($con, $_POST["search_query"]);

                    // Build the SQL query to search for friends by ID or Name
                    $query = "SELECT f.id_sender, u.name as friend_name, u.email as friend_email, u.imgg as friend_imgg
                              FROM friend_user f
                              JOIN userlogin u ON f.id_sender = u.user_id
                              WHERE (f.id_recipient = '$userId' AND f.friend_type = 2)
                                AND (u.user_id LIKE '%$searchQuery%' OR u.name LIKE '%$searchQuery%')
                              UNION ALL
                              SELECT f.id_recipient, u.name as friend_name, u.email as friend_email, u.imgg as friend_imgg
                              FROM friend_user f
                              JOIN userlogin u ON f.id_recipient = u.user_id
                              WHERE (f.id_sender = '$userId' AND f.friend_type = 2)
                                AND (u.user_id LIKE '%$searchQuery%' OR u.name LIKE '%$searchQuery%')";

                    $result = mysqli_query($con, $query);

                    $count = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $friendId = $row["id_sender"];
                        $friendName = $row["friend_name"];
                        $friendEmail = $row["friend_email"];
                        $friendImgg = $row["friend_imgg"];
                        // ครอบสร้างรายการเพื่อนที่ตรงกับผลลัพธ์
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $friendId; ?></td>
                            <td><?php echo $friendName; ?></td>
                            <td><?php echo $friendEmail; ?></td>
                            <td>
                                <?php
                                // ตรวจสอบว่ามีรูปภาพหรือไม่
                                if (!empty($friendImgg) && file_exists($friendImgg)) {
                                    echo "<img class='circle-image' src='$friendImgg' width='50' height='50'>";
                                } else {
                                    // ถ้าไม่มีรูปภาพให้ใช้รูปภาพเริ่มต้น
                                    echo "<img class='circle-image' src='assets/images/person.png' width='50' height='50'>";
                                }
                                ?>
                            </td>
                            <td>
                                <a href="#" class="btn btn-danger delete-button"
                                    data-empid="<?php echo $friendId; ?>">ลบ</a>
                            </td>
                        </tr>
                        <?php
                        $count++;
                    }
                    // ตรวจสอบว่ามีผลลัพธ์หรือไม่
                    if ($count === 1) {
                        echo "<tr><td colspan='6'>No results found.</td></tr>";
                    }
                } else {
                    // If the search query is not set, display all friends
                    $query = "SELECT f.id_sender, u.name as friend_name, u.email as friend_email, u.imgg as friend_imgg
                              FROM friend_user f
                              JOIN userlogin u ON f.id_sender = u.user_id
                              WHERE f.id_recipient = '$userId' AND f.friend_type = 2
                              UNION ALL
                              SELECT f.id_recipient, u.name as friend_name, u.email as friend_email, u.imgg as friend_imgg
                              FROM friend_user f
                              JOIN userlogin u ON f.id_recipient = u.user_id
                              WHERE f.id_sender = '$userId' AND f.friend_type = 2";

                    $result = mysqli_query($con, $query);

                    $count = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $friendId = $row["id_sender"];
                        $friendName = $row["friend_name"];
                        $friendEmail = $row["friend_email"];
                        $friendImgg = $row["friend_imgg"];
                        // สร้างรายการเพื่อนทั้งหมด
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $friendId; ?></td>
                            <td><?php echo $friendName; ?></td>
                            <td><?php echo $friendEmail; ?></td>
                            <td>
                                <?php
                                // ตรวจสอบว่ามีรูปภาพหรือไม่
                                if (!empty($friendImgg) && file_exists($friendImgg)) {
                                    echo "<img class='circle-image' src='$friendImgg' width='50' height='50'>";
                                } else {
                                    // ถ้าไม่มีรูปภาพให้ใช้รูปภาพเริ่มต้น
                                    echo "<img class='circle-image' src='assets/images/person.png' width='50' height='50'>";
                                }
                                ?>
                            </td>
                            <td>
                                <a href="#" class="btn btn-danger delete-button"
                                    data-empid="<?php echo $friendId; ?>">ลบ</a>
                            </td>
                        </tr>
                        <?php
                        $count++;
                    }
                }
                mysqli_close($con);
                ?>
            </tbody>
        </table>

        <center><a href="indexuser.php" class="btn btn-primary mt-5">หน้าแรก</a><br><br>
    </div>
    </div>

    <!-- Include SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.6/dist/sweetalert2.min.css">

    <!-- Include SweetAlert JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.6/dist/sweetalert2.min.js"></script>

    <script>
    // Function to handle delete button click
    function handleDeleteClick(event) {
        event.preventDefault();

        const friendId = event.target.getAttribute("data-empid");

        Swal.fire({
            title: 'คุณต้องการลบเพื่อนหรือไม่?',
            text: "Once deleted, it cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, navigate to reject_from_friendlist.php with friendId
                window.location.href = `reject_from_friendlist.php?empId=${friendId}`;
            }
        });
    }

    // Attach click event handler to all delete buttons
    const deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', handleDeleteClick);
    });
    </script>
    <div style="height: 200px;"></div>
</body>
</html>